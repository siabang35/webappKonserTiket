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
            $concert = Concert::findOrFail($validated['concert_id']);

            // Hitung harga tiket
            $ticket_price = $validated['ticket_type'] === 'vip' ? $concert->price * 2 : $concert->price;
            $total_price = $ticket_price * $validated['ticket_count'];

            // Simpan data pesanan
            $order = Order::create([
                'user_id' => auth()->id(),
                'concert_id' => $concert->id,
                'ticket_type' => $validated['ticket_type'],
                'ticket_count' => $validated['ticket_count'],
                'total_price' => $total_price,
            ]);

            // Logging keberhasilan pesanan
            Log::info('Order berhasil dibuat', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'concert_id' => $concert->id,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('order.konfirmasi')->with('success', 'Tiket berhasil dipesan!');
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

            return view('orders.history', compact('orders')); // Perbaiki nama view sesuai konvensi
        } catch (\Exception $e) {
            // Logging kesalahan
            Log::error('Error saat mengambil riwayat pemesanan: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('dashboard')->withErrors('Gagal memuat riwayat pemesanan. Silakan coba lagi.');
        }
    }
}
