<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Events\PaymentCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    public function processPayment(Order $order, array $paymentDetails, $paymentMethod)
    {
        try {
            if (!$order) {
                throw new Exception('Order not found');
            }

            DB::beginTransaction();

            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = !config('app.debug');

            $transactionDetails = [
                'order_id' => $order->id,
                'gross_amount' => (int) $order->total_amount,
            ];

            $customerDetails = [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'enable_payments' => [$paymentMethod], // Menggunakan metode pembayaran yang dipilih
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => 60
                ]
            ];

            // Pastikan Snap Token berhasil dibuat
            try {
                $snapToken = \Midtrans\Snap::getSnapToken($transactionData);
                $paymentUrl = \Midtrans\Snap::getSnapUrl($transactionData);
            } catch (Exception $e) {
                Log::error('Midtrans error: ' . $e->getMessage());
                throw new Exception('Failed to generate Midtrans payment link');
            }

            // Buat transaksi pembayaran
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => $paymentMethod, // Menyimpan metode pembayaran yang dipilih
                'status' => 'pending',
                'payment_details' => json_encode([
                    'snap_token' => $snapToken,
                    'payment_url' => $paymentUrl
                ])
            ]);

            $order->update(['status' => 'pending']);

            DB::commit();

            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage());
            throw $e;
        }
    }



public function handlePaymentNotification(array $notification)
{
    try {
        DB::beginTransaction();

        Log::info('Midtrans notification received: ', $notification);

        // Pastikan `transaction_id` ada di data notifikasi
        if (!isset($notification['transaction_id'])) {
            throw new Exception('Transaction ID is missing in Midtrans notification');
        }

        $payment = Payment::where('transaction_id', $notification['transaction_id'])->first();

        if (!$payment) {
            throw new Exception('Payment not found for transaction ID: ' . $notification['transaction_id']);
        }

        $order = $payment->order;

        $newStatus = match ($notification['transaction_status']) {
            'capture', 'settlement' => 'completed',
            'pending' => 'pending',
            'deny', 'expire', 'cancel' => 'cancelled',
            default => 'failed'
        };

        $payment->update([
            'status' => $newStatus,
            'payment_details' => json_encode(array_merge(
                json_decode($payment->payment_details, true) ?? [],
                ['notification' => $notification]
            ))
        ]);

        $order->update(['status' => $newStatus]);

        if ($newStatus === 'completed') {
            event(new PaymentCompleted($payment));
        }

        DB::commit();

        return $payment;
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Payment notification handling failed: ' . $e->getMessage());
        throw $e;
    }
}


    public function processRefund(Order $order)
    {
        try {
            DB::beginTransaction();

            $payment = $order->payment;

            if (!$payment) {
                throw new Exception('No payment found for this order');
            }

            // Process refund through payment gateway
            $refundResponse = \Midtrans\Transaction::refund(
                $payment->transaction_id,
                [
                    'amount' => $payment->amount,
                    'reason' => 'Customer requested refund'
                ]
            );

            $payment->update([
                'status' => 'refunded',
                'payment_details' => array_merge(
                    $payment->payment_details ?? [],
                    ['refund' => $refundResponse]
                )
            ]);

            $order->update(['status' => 'refunded']);

            DB::commit();

            return $refundResponse;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Refund processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
