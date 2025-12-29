<?php

namespace App\Mail\Restaurant;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Generate PDF receipt
        $pdf = Pdf::loadView('Restaurant.emails.receipt', ['order' => $this->order]);
        return $this->subject('Order Confirmation')
                    ->view('Restaurant.emails.order_confirmation')
                    ->attachData($pdf->output(), "receipt_{$this->order->id}.pdf");
    }

}
