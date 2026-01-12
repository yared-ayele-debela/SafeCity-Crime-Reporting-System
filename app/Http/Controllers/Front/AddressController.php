<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AddressController extends Controller
{
    //
    public function getDeliveryAddress(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = $request->all();
                // $deliveryAddress=DeliveryAddress::deliveryAddresses();
                $deliveryAddress = DeliveryAddress::where('id', $data['addressid'])->first()->toArray();
                return response()->json(['address' => $deliveryAddress]);
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function saveDeliveryAddress(Request $request)
    {
        try {
            if (!$request->method('post')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }

            $validator = Validator::make($request->all(), [
                'delivery_name' => 'required|string|max:100',
                'delivery_address' => 'required|string|max:100',
                'delivery_city' => 'required|string|max:100',
                'delivery_state' => 'required|string|max:100',
                'delivery_country' => 'required|string|max:100',
                'delivery_mobile' => 'required|digits:10',
            ]);
            if ($validator->fails()) {
                Alert::toast('Please fill in the complete delivery address information correctly before submitting.','error');
                return redirect()->back();
            }

            if ($request->has('delivery_id') && !empty($request->input('delivery_id'))) {
                $deliveryAddress = DeliveryAddress::find($request->input('delivery_id'));
                $deliveryAddress->user_id = Auth::user()->id;
                $deliveryAddress->name = $request->input('delivery_name');
                $deliveryAddress->address = $request->input('delivery_address');
                $deliveryAddress->city = $request->input('delivery_city');
                $deliveryAddress->state = $request->input('delivery_state');
                $deliveryAddress->country = $request->input('delivery_country');
                $deliveryAddress->mobile = $request->input('delivery_mobile');
                $deliveryAddress->pincode = $request->input('delivery_pincode');
                // dd($request->all());
                // Save the data into the delivery_address table
                $deliveryAddress->update();
                Alert::toast('Delivery Address Updated Successfully !', 'success');
            } else {

                $deliveryAddress = new DeliveryAddress();
                $deliveryAddress->user_id = Auth::user()->id;
                $deliveryAddress->name = $request->input('delivery_name');
                $deliveryAddress->address = $request->input('delivery_address');
                $deliveryAddress->city = $request->input('delivery_city');
                $deliveryAddress->state = $request->input('delivery_state');
                $deliveryAddress->country = $request->input('delivery_country');
                $deliveryAddress->mobile = $request->input('delivery_mobile');
                $deliveryAddress->pincode = $request->input('delivery_pincode');
                // dd($request->all());

                // Save the data into the delivery_address table
                $deliveryAddress->save();

                Alert::toast('Delivery address  has been created!', 'success');
            }
            return redirect('checkout');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function removeDeliveryAddress($id)
    {
        try {
            DeliveryAddress::where('id', $id)->delete();

            Alert::toast('Delivery address deleted successfully !', 'error');

            return redirect('checkout');
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
