<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf; // Ubah dari $tickets menjadi $pdf

    public function __construct(Order $order, $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Your Concert Tickets - ' . $this->order->concert->name)
                    ->view('emails.tickets')
                    ->attachData($this->pdf, 'tickets-' . $this->order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}
