<?php

namespace App\Listeners;

use App\Models\Restaurant\RestaurantCartItem;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
class SyncCartSessionToDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
          $user = $event->user;
            $cart = Session::get('cart', []);

            foreach ($cart as $item) {
                RestaurantCartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            Session::forget('cart');
    }
}