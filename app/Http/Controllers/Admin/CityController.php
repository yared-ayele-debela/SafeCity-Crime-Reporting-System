<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\City;
use App\Models\State;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CityController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view city')) {
                return view('admin.errors.unauthorized');
            }
            $cities = City::with('states')->get();
            // dd($cities);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.city.index', compact('cities', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('add city')) {
                return view('admin.errors.unauthorized');
            }
            $states=State::all();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.city.create', compact('appsettings','states'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('add city')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }

            $request->validate([
                'name' => 'required|unique:cities'
            ]);

            $city = new City();
            $city->name = $request->input('name');
            $city->states_id = $request->input('state_id');
            $city->status = 1;
            $city->save();


                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Cities', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('City has been saved successfully!', 'success');
            return redirect()->route('cities');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit city')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::find($id);
            $states=State::all();
            $appsettings = AppSetting::all()->toArray();

            return view('admin.city.edit', compact('city', 'appsettings','states'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit city')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }
            $request->validate([
                'name' => 'required'
            ]);

            $city = City::find($request->input('id'));
            $city->name = $request->input('name');
            $city->states_id = $request->input('state_id');
            $city->save();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Cities', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('City has been updated successfully!', 'success');
            return redirect()->route('cities');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete city')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::find($id);
            $city->delete();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Cities', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('City has been deleted successfully!', 'error');
            return redirect()->route('cities');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit city')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::find($id);
            $city->status = 1;
            $city->save();

            Alert::toast('City has been activated!', 'success');
            return redirect()->route('cities');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit city')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::find($id);
            $city->status = 0;
            $city->save();

            Alert::toast('City has been inactivated successfully!', 'error');
            return redirect()->route('cities');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
