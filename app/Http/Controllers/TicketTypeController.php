<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TicketTypeController extends Controller
{
    public function generateTicket(Order $order)
{
    if ($order->status !== Order::STATUS_COMPLETED) {
        throw new \Exception('Order belum selesai dibayar');
    }

    try {
        $tickets = [];

        // Generate tiket untuk setiap pembelian
        for ($i = 0; $i < $order->ticket_count; $i++) {
            $ticket = new Ticket([
                'concert_id' => $order->concert_id,
                'ticket_type_id' => $order->ticket_type_id,
                'user_id' => $order->user_id,
                'price' => $order->total_price / $order->ticket_count,
                'status' => Ticket::STATUS_SOLD,
                'ticket_code' => $this->generateUniqueTicketCode(),
                'valid_until' => $order->concert->date->addDays(1),
            ]);

            // Simpan tiket agar mendapatkan ID dari database
            $ticket->save();

            // Generate QR code setelah tiket memiliki ID
            $qrData = [
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'concert_id' => $ticket->concert_id,
                'user_id' => $ticket->user_id,
                'concert_name' => $order->concert->name,
                'concert_date' => $order->concert->date->format('Y-m-d H:i:s'),
                'ticket_type' => $order->ticket_type,
                'user_name' => $order->user->name,
            ];

            $ticket->qr_code = base64_encode(json_encode($qrData));
            $ticket->save(); // Simpan lagi setelah QR code dibuat

            $tickets[] = $ticket;
        }

        return $tickets; // Kembalikan array tiket, bukan response JSON

    } catch (\Exception $e) {
        throw new \Exception('Gagal generate tiket: ' . $e->getMessage());
    }
}


    private function generateUniqueTicketCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Ticket::where('ticket_code', $code)->exists());

        return $code;
    }

    public function generatePDF(Order $order)
{
    $tickets = $order->tickets;
    $concert = $order->concert;

    $data = [
        'order' => $order,
        'tickets' => $tickets,
        'concert' => $concert,
    ];

    $pdf = Pdf::loadView('tickets.pdf', $data);
    return $pdf->output(); // Pastikan mengembalikan data, bukan langsung `download()`
}
}
