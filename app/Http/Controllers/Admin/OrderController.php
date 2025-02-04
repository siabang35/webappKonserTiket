<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Concert;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;
    protected $paymentService;

    public function index()
    {
        $orders = Order::with('concert')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }


    public function __construct(
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'concert_id' => 'required|exists:concerts,id',
                'ticket_type' => 'required|in:reguler,vip',
                'ticket_count' => 'required|integer|min:1|max:10',
                'payment_method' => 'required|string'
            ]);

            DB::beginTransaction();

            $concert = Concert::findOrFail($validated['concert_id']);

            if ($concert->tickets_left < $validated['ticket_count']) {
                return back()->withError('Maaf, jumlah tiket yang tersedia tidak mencukupi.');
            }

            $basePrice = $concert->price;
            if ($validated['ticket_type'] === 'vip') {
                $basePrice *= 2;
            }

            $totalAmount = $basePrice * $validated['ticket_count'];

            $order = Order::create([
                'user_id' => auth()->id(),
                'concert_id' => $validated['concert_id'],
                'ticket_type' => $validated['ticket_type'],
                'ticket_count' => $validated['ticket_count'],
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            $payment = $this->paymentService->processPayment($order, [
                'payment_method' => $validated['payment_method']
            ]);

            DB::commit();

            return view('orders.payment', [
                'order' => $order,
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->withError('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['concert', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function paymentCallback(Request $request)
    {
        try {
            $notification = $request->all();

            $payment = $this->paymentService->handlePaymentNotification($notification);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment callback failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
