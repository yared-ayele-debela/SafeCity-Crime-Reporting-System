<?php

use App\Events\DeliveryManLocationUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Delivery\DeliveryManLocationUpdateController;
use App\Models\DeliveryMan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/deliveryman/update-location', [DeliveryManLocationUpdateController::class, 'updateLocation']);

Route::get('/orders/{order}/deliveryman-location', action: [DeliveryManLocationUpdateController::class, 'getDeliverymanLocation']);


Route::post('/update-location', function (Request $request) {
    $request->validate([
        'id' => 'required|exists:deliverymen,id',
        'lat' => 'required|numeric',
        'long' => 'required|numeric',
    ]);

    $man = DeliveryMan::find($request->id);
    $man->update([
        'current_lat' => $request->lat,
        'current_lng' => $request->long,
    ]);

    broadcast(new DeliveryManLocationUpdated(
        $man->id,
        $request->lat,
        $request->long
    ))->toOthers();

    return response()->json(['status' => 'Location updated']);
});