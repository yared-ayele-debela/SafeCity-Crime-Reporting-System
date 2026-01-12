<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\DeliveryAddress;
use App\Models\State;
use App\Models\Street;
use App\Models\SubCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDeliveryAddressController extends Controller
{
    public function index()
    {
        $addresses = DeliveryAddress::where('user_id', Auth::user()->id)->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:15',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DeliveryAddress::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'sub_city' => $request->sub_city,
            'street' => $request->street,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
            'mobile' => $request->mobile,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        return redirect()->back()->with('success', 'Address saved successfully!');

    }
     // Delete an address
     public function destroy($id)
     {
         $address = DeliveryAddress::where('id', $id)->where('user_id', Auth::id())->first();

         if (!$address) {
             return response()->json(['error' => 'Address not found'], 404);
         }

         $address->delete();
         return response()->json(['success' => 'Address deleted successfully']);
     }

    public function getRegions($countryId)
    {
        $regions = State::where('country_id', $countryId)->get();
        return response()->json($regions);
    }

    public function getCities($stateId)
    {
        $cities = City::where('states_id', $stateId)->get();
        return response()->json($cities);
    }

    public function getSubCities($cityId)
    {
        $subCities = SubCity::where('city_id', $cityId)->get();
        return response()->json($subCities);
    }

    public function getStreets($subCityId)
    {
        $streets = Street::where('sub_city_id', $subCityId)->get();
        return response()->json($streets);
    }
}
