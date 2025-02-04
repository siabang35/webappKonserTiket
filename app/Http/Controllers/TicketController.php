<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Ticket; // Tambahkan ini

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::paginate(10); // Menampilkan 10 tiket per halaman
        return view('tickets.index', compact('tickets'));
    }

    public function purchase(Concert $concert, $type)
    {
        // Logika untuk memilih tiket berdasarkan konser dan tipe
        // Misalnya menampilkan informasi konser dan tipe tiket
        return view('tickets.purchase', compact('concert', 'type'));
    }
}
