<?php

namespace App\Http\Controllers;

use App\Models\Restaurant\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderRouteMapController extends Controller
{
    //
      public function show()
    {
        $order = Order::findOrFail(23);

        return view('route-map', [
            'order' => $order,
            'apiKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjb21wYW55bmFtZSI6IkVhc3kgZS1jb21tZXJjZSBob3RlbCBib29raW5nIGFuZCBkZWxpdmVyeSIsImRlc2NyaXB0aW9uIjoiMGU4ZDhhZDMtZmJhYy00OTJkLWE4OWYtZGFiZjQxNTFlNDc2IiwiaWQiOiI3OWY1ODRlYy0yZDA3LTRjNWQtYTI2Ny00MjBhNzVlMDY2NzMiLCJ1c2VybmFtZSI6ImJlZmk3NzU2In0.JgSoBiAoa4Te6ccg-jSJSifq26PZV4FnGbkhQKiTnuo'
        ]);
    }

    public function getRoute(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');


        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjb21wYW55bmFtZSI6IkVhc3kgZS1jb21tZXJjZSBob3RlbCBib29raW5nIGFuZCBkZWxpdmVyeSIsImRlc2NyaXB0aW9uIjoiMGU4ZDhhZDMtZmJhYy00OTJkLWE4OWYtZGFiZjQxNTFlNDc2IiwiaWQiOiI3OWY1ODRlYy0yZDA3LTRjNWQtYTI2Ny00MjBhNzVlMDY2NzMiLCJ1c2VybmFtZSI6ImJlZmk3NzU2In0.JgSoBiAoa4Te6ccg-jSJSifq26PZV4FnGbkhQKiTnuo';

        $response = Http::get("https://mapapi.gebeta.app/api/route/direction", [
            'origin' => $origin,
            'destination' => $destination,
            'apiKey' => $apiKey
        ]);

    if ($response->successful()) {
        return response()->json(data: $response->json());
    }
        return response()->json(['error' => 'Route fetch failed'], 500);
    }
}
