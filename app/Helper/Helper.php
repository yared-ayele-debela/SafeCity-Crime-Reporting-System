<?php

namespace App\Helper;


use App\Models\Cart;
use App\Models\Currencies;
use App\Models\Notification;
use App\Models\Restaurant\RestaurantCartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function Clue\StreamFilter\fun;

// start for currency converter
class Helper
{


     public static function getStatusColor($status)
    {
        return match ($status) {
            'open' => 'info',
            'closed' => 'dark',
            'pending' => 'warning',
            'in_progress' => 'secondary',
            'rejected' => 'danger',
            'resolved' => 'success',
            default => 'primary',
        };
    }
     public static function calculateDeliveryCommission($order)
    {
        $commissionRate = 0.02;
        // dd($total_commuction);
        return round($order->total * $commissionRate, 2);
    }
    public static function totalCartItems()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $totalCartItems = Cart::where('user_id', $user_id)->sum('quantity');
        } else {
            $session_id = Session::get('session_id');
            $totalCartItems = Cart::where('session_id', $session_id)->sum('quantity');
        }
        return $totalCartItems;
    }

    public static function totalRestaurantCartItems()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $totalRestaurantCartItems = RestaurantCartItem::where('user_id', $user_id)->sum('quantity');
        } else {
            $totalRestaurantCartItems = collect(Session::get('cart', []))->sum('quantity');
        }
        return $totalRestaurantCartItems;
    }
    public static function RestaurantCartItems(){
        if(Auth::check()){
            $user_id = Auth::user()->id;
            $RestaurantCartItems = RestaurantCartItem::where('user_id', $user_id)->get();
            } else {
              $RestaurantCartItems= collect(Session::get('cart', []));
            }
            return $RestaurantCartItems;
    }

    public static function getCartItems()
    {
        if (Auth::check()) {
            $getCartItems = Cart::with(['product' => function ($query) {
                $query->select('id', 'category_id', 'product_name', 'product_code', 'product_color', 'product_image');
            }])->orderby('id', 'Desc')->where('user_id', Auth::user()->id)->get()->toArray();
        } else {
            $getCartItems = Cart::with(['product' => function ($query) {
                $query->select('id', 'category_id', 'product_name', 'product_code', 'product_color', 'product_image');
            }])->orderby('id', 'Desc')->where('session_id', Session::get('session_id'))->get()->toArray();
        }
        return $getCartItems;
    }

    public static function totalWishlistItems()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $totalWishlistItems = \App\Models\Wishlist::where('user_id', $user_id)->count();
        }
        return $totalWishlistItems;
    }

    public static function currency_load()
    {
        if (session()->has('system_default_currency_info') == false) {
            session()->put('system_default_currency_info', Currencies::find(1));
        }
    }
    public static function currency_converter($amount)
    {
        return format_price(convert_price($amount));
    }
    public static function final_amount_currency_converter($amount)
    {
        return final_price(convert_price($amount));
    }
    public static function final_amount_currency_symbol()
    {
        return final_price_symbol();
    }
}

if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        Helper::currency_load();
        $system_default_currency_info = session('system_default_currency_info');
        $price = floatval($price) / floatval($system_default_currency_info->exchange_rate);
        // dd($system_default_currency_info->exchange_rate);
        if (Session::has('currency_exchange_rate')) {

            $exchange = session('currency_exchange_rate');
        } else {
            $exchange = $system_default_currency_info->exchange_rate;
        }
        $price = floatval($price) * floatval($exchange);

        return $price;
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        Helper::currency_load();
        if (session()->has('currency_symbol')) {
            $symbol = session('currency_symbol');
        } else {
            $system_default_currency_info = session('system_default_currency_info');
            $symbol = $system_default_currency_info->symbol;
        }
        return $symbol;
    }
}

if (!function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 2) . " " . currency_symbol();
    }

    if (!function_exists('final_price')) {
        function final_price($price)
        {
            return number_format($price, 2);
        }
    }
    if (!function_exists('final_price_symbol')) {
        function final_price_symbol()
        {
            return currency_symbol();
        }
    }
}