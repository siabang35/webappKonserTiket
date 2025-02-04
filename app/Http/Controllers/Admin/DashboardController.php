<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin'); // Pastikan admin sudah terautentikasi sebelum mengakses dashboard
    }

    public function index()
    {
        // Get summary statistics
        $stats = [
            'total_sales' => Order::where('status', 'completed')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_concerts' => Concert::count(),
        ];

        // Get recent orders
        $recentOrders = Order::with(['user', 'concert'])
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming concerts
        $upcomingConcerts = Concert::where('date', '>', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        // Get monthly sales data
        $monthlySales = Order::where('status', 'completed')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'upcomingConcerts',
            'monthlySales'
        ));
    }
}
