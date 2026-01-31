<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Country;
use App\Services\ActivityLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class CountryController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view country')) {
                return view('admin.errors.unauthorized');
            }
            $countries = Country::paginate(10);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.country.index', compact('countries', 'appsettings'));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function create()
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('add country')) {
            return view('admin.errors.unauthorized');
        }
        $appsettings = AppSetting::all()->toArray();
        return view('admin.country.create', compact('appsettings'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('add country')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('countries');
            }
            $request->validate([
                'country_name' => 'required|unique:apps_countries',
                'country_code' => 'required|unique:apps_countries|max:2'
            ]);

            $country = new Country();
            $country->country_name = $request->input('country_name');
            $country->country_code = $request->input('country_code');
            $country->status = 1;
            $country->save();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add country', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");



            Alert::toast('Country has been saved successfully!', 'success');
            return redirect()->route('countries');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (QueryException $e) {
            // Handle database query errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            // Catch other exceptions
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit country')) {
                return view('admin.errors.unauthorized');
            }
            $country = Country::findOrFail($id);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.country.edit', compact('country', 'appsettings'));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit country')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('countries');
            }

            $request->validate([
                'country_name' => 'required',
                'country_code' => 'required|max:2'
            ]);

            $country = Country::find($request->input('id'));
            $country->country_name = $request->input('country_name');
            $country->country_code = $request->input('country_code');
            $country->save();

               $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update country', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Country has been updated successfully!', 'success');
            return redirect()->route('countries');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (QueryException $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete country')) {
                return view('admin.errors.unauthorized');
            }
            $country = Country::find($id);
            $country->delete();

               $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete country', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Country has been deleted successfully!', 'success');
            return redirect()->route('countries');
        } catch (QueryException $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit country')) {
                return view('admin.errors.unauthorized');
            }
            $country = Country::find($id);
            $country->status = 1;
            $country->save();

            Alert::toast('Country has been activated!', 'success');
            return redirect()->route('countries');
        } catch (QueryException $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit country')) {
                return view('admin.errors.unauthorized');
            }
            $country = Country::find($id);
            $country->status = 0;
            $country->save();

            Alert::toast('Country has been inactivated!', 'success');
            return redirect()->route('countries');
        } catch (QueryException $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
