<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutOfStockRequestedNotification extends Notification
{
    use Queueable;


    public function __construct(public Product $product) {}

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable) {
        return [
            'message' => 'A customer requested restock for: ' . $this->product->name,
            'product_id' => $this->product->id,
        ];
    }
}
