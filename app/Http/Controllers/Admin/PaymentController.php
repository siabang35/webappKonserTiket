<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::with(['order.user', 'order.concert'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.concert']);
        return view('admin.payments.show', compact('payment'));
    }

    public function processRefund(Order $order)
    {
        try {
            $refundResponse = $this->paymentService->processRefund($order);

            return redirect()->back()->with('success', 'Refund processed successfully.');
        } catch (Exception $e) {
            Log::error('Refund processing error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process refund. ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = Payment::with(['order.user', 'order.concert'])
                ->when($startDate, function ($q) use ($startDate) {
                    return $q->whereDate('created_at', '>=', $startDate);
                })
                ->when($endDate, function ($q) use ($endDate) {
                    return $q->whereDate('created_at', '<=', $endDate);
                });

            $payments = $query->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="payments.csv"',
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($file, [
                    'Payment ID',
                    'Order ID',
                    'Customer',
                    'Concert',
                    'Amount',
                    'Payment Method',
                    'Status',
                    'Transaction ID',
                    'Created At'
                ]);

                // Add payment data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->id,
                        $payment->order_id,
                        $payment->order->user->name,
                        $payment->order->concert->name,
                        $payment->amount,
                        $payment->payment_method,
                        $payment->status,
                        $payment->transaction_id,
                        $payment->created_at
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        } catch (Exception $e) {
            Log::error('Payment export error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to export payments. ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $totalPayments = Payment::count();
        $successfulPayments = Payment::where('status', 'completed')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $recentPayments = Payment::with(['order.user', 'order.concert'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyRevenue = Payment::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->get();

        return view('admin.payments.dashboard', compact(
            'totalPayments',
            'successfulPayments',
            'totalRevenue',
            'recentPayments',
            'monthlyRevenue'
        ));
    }
}
