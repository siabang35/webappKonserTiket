<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Exception;
use Midtrans\Config;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Support\Facades\Mail;


class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
{
    $this->paymentService = $paymentService;

    // Ambil konfigurasi dari config/services.php
    $serverKey = config('services.midtrans.server_key');
    $isProduction = config('services.midtrans.is_production', false);

    // Pastikan server key tidak null
    if (!$serverKey) {
        throw new \Exception('Midtrans Server Key is not set. Please check your .env configuration.');
    }

    // Set konfigurasi Midtrans
    Config::$serverKey = $serverKey;
    Config::$isProduction = $isProduction;
    Config::$isSanitized = true;
    Config::$is3ds = true;
}


public function show(Order $order)
{
    $payment = $order->payment;

    if (!$payment) {
        return redirect()->back()->with('error', 'Payment not found for this order.');
    }

    return view('payments.show', compact('order', 'payment'));
}


public function process(Order $order, Request $request, PaymentService $paymentService)
{
    $paymentMethod = $request->input('payment_method');

    try {
        // Proses pembayaran dengan metode yang dipilih
        $payment = $paymentService->processPayment($order, $request->all(), $paymentMethod);

        $paymentDetails = json_decode($payment->payment_details, true);
        $redirectUrl = $paymentDetails['payment_url'] ?? '';

        // Jika URL pembayaran ditemukan, redirect ke halaman Midtrans
        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        // Jika URL tidak ditemukan, kirimkan error
        return response()->json(['error' => 'Payment URL not found'], 500);
    } catch (Exception $e) {
        // Tangani kesalahan
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // Method untuk menginisiasi pembayaran
    public function initiatePayment(Request $request, Order $order)
{
    // Ambil data yang dibutuhkan dari order
    $totalAmount = $order->total_amount;
    $ticketType = $order->ticket_type;
    $ticketQuantity = $order->ticket_count;

   // Ubah format order ID untuk Midtrans
   $transactionDetails = [
    'order_id' => 'ORDER-' . $order->id, // Tambahkan prefix untuk membedakan
    'gross_amount' => $order->total_amount,
];

    // Tentukan item yang dibeli
    $items = [
        [
            'id' => 'ticket_' . $ticketType,
            'price' => $totalAmount / max(1, $ticketQuantity), // Hindari pembagian dengan nol
            'quantity' => $ticketQuantity,
            'name' => ucfirst($ticketType) . ' Ticket'
        ]
    ];

    // Pastikan data user tersedia
    $customer = $order->user ?? null;
    $customerDetails = [
        'first_name' => $customer->first_name ?? 'Customer',
        'last_name' => $customer->last_name ?? '',
        'email' => $customer->email ?? 'customer@example.com',
        'phone' => $customer->phone ?? '08123456789',
    ];

    // Data untuk transaksi Midtrans
    $transactionData = [
        'transaction_details' => $transactionDetails,
        'item_details' => $items,
        'customer_details' => $customerDetails
    ];

    try {
        // Menghasilkan token dari Midtrans untuk transaksi
        $snapToken = Snap::getSnapToken($transactionData);

        return response()->json([
            'status_code' => 200,
            'redirect_url' => "https://app.sandbox.midtrans.com/snap/v4/transaction/{$snapToken}"
        ]);
    } catch (\Exception $e) {
        Log::error('Midtrans payment initiation failed: ' . $e->getMessage());
        return response()->json([
            'status_code' => 500,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

public function handleWebhook(Request $request)
{
    Log::info('Webhook received with payload:', $request->all());

    try {
        $serverKey = config('services.midtrans.server_key');
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $transactionStatus = $request->input('transaction_status');
        $receivedSignature = $request->input('signature_key');

        if (empty($orderId)) {
            Log::error("Invalid webhook: Missing order_id");
            return response()->json(['error' => 'Missing order_id'], 400);
        }

        Log::info('Original Midtrans order ID:', ['orderId' => $orderId]);

        // Ambil ID numerik dari 'ORDER-123456'
        $actualOrderId = preg_replace('/^ORDER-/', '', $orderId);

        if (!ctype_digit($actualOrderId)) {
            Log::error("Invalid order ID format", ['orderId' => $orderId, 'actualOrderId' => $actualOrderId]);
            return response()->json(['error' => 'Invalid order ID format'], 400);
        }

        Log::info('Processed Order ID:', ['actualOrderId' => $actualOrderId]);

        // Cari Order berdasarkan order_id
        $order = Order::where('order_id', $actualOrderId)->first();
        if (!$order) {
            Log::error("Order not found", ['actualOrderId' => $actualOrderId]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Validasi signature Midtrans
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($receivedSignature !== $calculatedSignature) {
            Log::error("Signature mismatch", [
                'received' => $receivedSignature,
                'calculated' => $calculatedSignature
            ]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        Log::info("Processing transaction for order:", [
            'order_id' => $order->order_id,
            'status' => $transactionStatus,
            'current_db_status' => $order->status
        ]);

        DB::beginTransaction();

        try {
            switch ($transactionStatus) {
                case 'capture':
                case 'settlement':
                    if ($order->status !== 'paid') {
                        $order->update(['status' => 'paid']);
                        Log::info("Order {$order->order_id} updated to PAID status");

                        if ($order->user && $order->user->email) {
                            try {
                                $ticketController = app(TicketTypeController::class);
                                $tickets = $ticketController->generateTicket($order);

                                if (!empty($tickets)) {
                                    $pdf = $ticketController->generatePDF($order);
                                    Mail::to($order->user->email)->send(new TicketMail($order, $pdf));
                                    Log::info("Ticket email sent to {$order->user->email}");
                                } else {
                                    Log::warning("No tickets generated for order {$order->order_id}");
                                }
                            } catch (\Throwable $e) {
                                Log::error("Error generating ticket for order {$order->order_id}: " . $e->getMessage());
                            }
                        }
                    }
                    break;

                case 'pending':
                    if ($order->status !== 'paid') {
                        $order->update(['status' => 'pending']);
                        Log::info("Order {$order->order_id} status updated to PENDING");
                    }
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    if ($order->status !== 'paid') {
                        $order->update(['status' => 'failed']);
                        Log::info("Order {$order->order_id} status updated to FAILED");
                    }
                    break;

                default:
                    Log::warning("Unhandled transaction status: {$transactionStatus}");
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Order {$order->order_id} processed successfully"
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed processing order {$order->order_id}: " . $e->getMessage());
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    } catch (Exception $e) {
        Log::error("Webhook Processing Error: " . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}



public function success(Order $order)
{
    if ($order->status === 'pending') {
        $order->update(['status' => 'paid']);
    }

    return view('payments.success', compact('order'));
}

public function failed(Order $order)
{
    return view('payments.failed', compact('order'));
}

public function handleCallback(Request $request, TicketTypeController $ticketController)
{
    $notification = $request->all();
    $payment = $this->paymentService->handlePaymentNotification($notification);

    if (!$payment || !$payment->order) {
        return redirect()->route('payment.failed')->with('error', 'Invalid payment data');
    }

    if ($payment->status === 'completed') {
    try {
        $tickets = $ticketController->generateTicket($payment->order);

        if (!empty($tickets)) {
            Mail::to($payment->order->user->email)->send(new TicketMail($payment->order, $tickets));
        } else {
            Log::warning("Tickets are empty for order ID: " . $payment->order->id);
        }

        return redirect()->route('payment.success', ['order' => $payment->order->id])
            ->with('success', 'Payment successful. Ticket sent to your email.');
    } catch (\Exception $e) {
        Log::error("Failed to send ticket: " . $e->getMessage());
        return redirect()->route('payment.failed', ['order' => $payment->order->id])
            ->with('error', 'Failed to send ticket: ' . $e->getMessage());
    }
}

}
}
