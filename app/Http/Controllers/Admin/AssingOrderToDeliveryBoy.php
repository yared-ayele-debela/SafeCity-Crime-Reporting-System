<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class AssingOrderToDeliveryBoy extends Controller
{
    //
    public function assignToDeliveryBoy(Request $request)
    {
        if (!$request->isMethod('put')) {
            // Handle the error - Method not allowed
            Alert::toast('Method not allowed', 'error');
            return back();
        }
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('assing_delivery_boy_to_order')) {
            return view('admin.errors.unauthorized');
        }

        try {
            $order = Order::find($request->input('order_id'));

            if ($order && $order->delivery_boy_id == $request->input('delivery_boy_id')) {
                Alert::toast('This order is already assigned to this delivery boy', 'error');
                return back();
            }


            $order->delivery_boy_id = $request->input('delivery_boy_id');
            $order->save();

            $delvieryboy=DeliveryMan::where('id',$request->input('delivery_boy_id'))->first();
            // dd($delvieryboy);
            $delvieryboy->status="delivering";
            $delvieryboy->update();

            $deliveryDetails = DeliveryMan::select('first_name', 'last_name', 'email')
                ->where('id', $request->input('delivery_boy_id'))
                ->first()
                ->toArray();

            $orderDetails = Order::with('orders_products')
                ->where('id', $request->input('order_id'))
                ->first()
                ->toArray();

            $email_template = EmailTemplate::first();

            $email = $deliveryDetails['email'];
            $messageData = [
                'email_template' => $email_template,
                'email' => $email,
                'first_name' => $deliveryDetails['first_name'],
                'last_name' => $deliveryDetails['last_name'],
                'order_id' => $request->input('order_id'),
                'orderDetails' => $orderDetails,
                'order_status' => $request->input('order_status'),
            ];

              $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Assign order to delivery man', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Mail::send('emails.assing_order_to_delivery_boy', $messageData, function ($message) use ($email) {
                $message->to($email)->subject('New order has been assigned to you for delivery - Byt Developers.in');
            });



            Alert::toast('Order has been assigned to the delivery boy!', 'success');

            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
