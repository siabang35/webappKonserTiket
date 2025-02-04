<?php

namespace App\Services;

use App\Models\Order;
use App\Exports\OrdersExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Facades\LogActivity;

class OrderService
{
    public function updateOrderStatus(Order $order, $status)
    {
        $order->update(['status' => $status]);

        activity()
            ->performedOn($order)
            ->log("Order status updated to {$status}");
    }

    public function exportOrders($filters)
    {
        $orders = Order::with(['user', 'concert'])
            ->when(isset($filters['status']), function($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['date_from']), function($query) use ($filters) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function($query) use ($filters) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            })
            ->get();

        return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }
}
