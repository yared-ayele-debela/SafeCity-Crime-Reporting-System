<?php

namespace App\Http\Controllers;

use App\Models\Restaurant\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NearbyDeliveryMenMapController extends Controller
{
    //
    public function index(){
        
        return view('nearby');
    }

    public function getNearbyDeliveryMen($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Get the first product's restaurant (assuming all items in the order are from the same restaurant)
        $orderItem = $order->orderItems()->first();
        $product = $orderItem ? $orderItem->product : null;
        $restaurant = $product ? $product->restaurant : null;

        if (!$restaurant) {
            abort(404, 'Restaurant not found for this order.');
        }

        $maxDistanceKm = 35;

        $deliverymen = DB::table('deliverymen')
            ->selectRaw("
                id, first_name,last_name,phone,address, current_lat, current_lng,
                (6371 * acos(cos(radians(?)) * cos(radians(current_lat)) *
                cos(radians(current_lng) - radians(?)) +
                sin(radians(?)) * sin(radians(current_lat)))) AS distance
            ", bindings: [$restaurant->latitude, $restaurant->longitude, $restaurant->latitude])
            ->having('distance', '<=', $maxDistanceKm)
            ->orderBy('distance')
            ->get();

        return response()->json($deliverymen);
    }

}