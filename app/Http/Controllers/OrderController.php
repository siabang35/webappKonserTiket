<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Concert;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menyimpan pemesanan tiket konser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'concert_id' => 'required|exists:concerts,id', // Validasi bahwa concert_id ada di tabel concerts
            'ticket_type' => 'required|string|in:reguler,vip',
            'ticket_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Ambil konser berdasarkan ID
            $concert = Concert::find($validated['concert_id']);

            // Periksa apakah konser sedang dalam promosi
            $ticket_price = $concert->is_promotion
                ? ($validated['ticket_type'] === 'vip' ? $concert->promotion_price * 2 : $concert->promotion_price)
                : ($validated['ticket_type'] === 'vip' ? $concert->price * 2 : $concert->price);

            // Hitung total harga
            $total_price = $ticket_price * $validated['ticket_count'];

            // Optional: Calculate a different amount for the "total_amount" if necessary (e.g., with a discount or additional fee)
            $total_amount = $total_price; // You can modify this based on your business logic

            // Set the initial order status (e.g., 'pending', 'paid', etc.)
            $status = 'pending';

            // Simpan data pesanan
            $order = Order::create([
                'user_id' => auth()->id(),
                'concert_id' => $concert->id,
                'ticket_type' => $validated['ticket_type'],
                'ticket_count' => $validated['ticket_count'],
                'total_price' => $total_price,
                'total_amount' => $total_amount, // Use the correct calculation here
                'status' => $status, // Set the initial status as 'pending'
            ]);

            // Logging keberhasilan pesanan
            Log::info('Order berhasil dibuat', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'concert_id' => $concert->id,
                'ticket_type' => $validated['ticket_type'],
                'total_price' => $total_price,
                'status' => $status,
            ]);
             // Ambil data dari request atau set nilai lainnya
        $concertId = $validated['concert_id'];
        $ticketType = $validated['ticket_type'];
        $ticketCount = $validated['ticket_count'];
        $totalPrice = $validated['total_price'];
        $totalAmount = $validated['total_amount'];

            // Redirect ke halaman konfirmasi dengan data pesanan
            return redirect()->route('orders.store')->with('order', $order);


        } catch (\Exception $e) {
            // Logging kesalahan
            Log::error('Error saat memproses pemesanan: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'concert_id' => $validated['concert_id'] ?? null,
            ]);
            return back()->withErrors('Gagal memesan tiket. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan konfirmasi pemesanan.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function konfirmasi()
    {
        // Get the 'order' data from the session
        $order = session('order');

        // Check if the order exists in the session
        if (!$order) {
            return redirect()->route('order.store')->withErrors('Order tidak ditemukan.');
        }

        // Return the konfirmasi view with the order data
        return view('orders.store', compact('order'));
    }

    /**
     * Menampilkan riwayat pesanan pengguna.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function history()
    {
        try {
            // Ambil riwayat pesanan untuk pengguna yang sedang login
            $orders = Order::where('user_id', auth()->id())
                ->with('concert') // Relasi ke model Concert
                ->latest()
                ->get();

            return view('orders.history', compact('orders'));
        } catch (\Exception $e) {
            // Logging kesalahan
            Log::error('Error saat mengambil riwayat pemesanan: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('dashboard')->withErrors('Gagal memuat riwayat pemesanan. Silakan coba lagi.');
        }
    }
}
