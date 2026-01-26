<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeasonalProductEndingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $products;
    public $nexMonth;

    /**
     * Create a new message instance.
     */
    public function __construct($products,$nexMonth)
    {
        $this->products= $products;
        $this->nexMonth= $nexMonth;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seasonal Product Ending Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.seasonal_products_ending',
            with:[
                'products'=>$this->products,
                'nextMonth'=>$this->nexMonth,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
