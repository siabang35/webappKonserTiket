<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan ada pengguna yang sedang login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to log in first');
        }

        // Mengambil konser yang ada
        $concerts = Concert::all();
        $featuredConcerts = Concert::where('date', '>=', now())->orderBy('date')->take(3)->get();
        $upcomingConcerts = Concert::where('date', '>=', now())->orderBy('date')->take(5)->get();

        // Mengambil data pesanan
        $totalOrders = auth()->user()->orders()->count();
        $vipOrders = auth()->user()->orders()->where('ticket_type', 'vip')->count();
        $totalSpent = auth()->user()->orders()->sum('total_price');
        $recentOrders = auth()->user()->orders()->with('concert')->latest()->take(5)->get();

        // Pilih concert_id dari konser tertentu atau setkan default
        $concertId = $concerts->first()->id; // Misalnya pilih konser pertama sebagai default

        // Membuat pesanan baru
        $order = new Order();
        $order->user_id = Auth::id(); // ID pengguna yang sedang login
        $order->concert_id = $concertId; // Menambahkan concert_id
        $order->ticket_count = 1; // Misalnya set jumlah tiket menjadi 1
        $order->ticket_type = 'regular'; // Sesuaikan dengan tipe tiket yang dipilih
        $order->status = 'pending'; // Status sementara, bisa diperbarui nanti
        $order->total_price = 0; // Sesuaikan harga tiket (sebelum dihitung total_amount)

        // Hitung total_amount berdasarkan ticket_count dan harga tiket
        $ticketPrice = 100; // Misalnya harga tiket adalah 100
        $order->total_amount = $ticketPrice * $order->ticket_count; // Total jumlah yang harus dibayar

        $order->save();

        // Mengirim data ke view
        return view('dashboard', compact(
            'concerts',
            'featuredConcerts',
            'upcomingConcerts',
            'totalOrders',
            'vipOrders',
            'totalSpent',
            'recentOrders',
            'order' // Mengirimkan order yang baru dibuat
        ));
    }

}
