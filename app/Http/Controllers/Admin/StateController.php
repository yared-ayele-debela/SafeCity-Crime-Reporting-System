<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Country;
use App\Models\State;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class StateController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view state')) {
                return view('admin.errors.unauthorized');
            }
            $states = State::with('country')->get();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.state.index', compact('states', 'appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('add state')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();
            $countries=Country::all();
            return view('admin.state.create', compact('appsettings','countries'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('add state')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->method('post')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $request->validate([
                'name' => 'required|unique:states'
            ]);

            $state = new State();
            $state->name = $request->input('name');
            $state->country_id = $request->input('country_id');
            $state->status = 1;
            $state->save();

              $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add State', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('State has been added', 'success');
            return redirect()->route('states');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit state')) {
                return view('admin.errors.unauthorized');
            }
            $state = State::find($id);
            $countries=Country::all();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.state.edit', compact('state', 'appsettings','countries'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit state')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->method('put')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $request->validate([
                'name' => 'required'
            ]);

            $state = State::find($request->input('id'));
            $state->name = $request->input('name');
            $state->country_id = $request->input('country_id');
            $state->save();

                          $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Update State', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        
            Alert::toast('State has been updated', 'success');
            return redirect()->route('states');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete state')) {
                return view('admin.errors.unauthorized');
            }
            $state = State::find($id);
            $state->delete();


                          $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete State', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('State has been deleted', 'error');
            return redirect()->route('states');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit state')) {
                return view('admin.errors.unauthorized');
            }
            $state = State::find($id);
            $state->status = 1;
            $state->save();

            Alert::toast('State has been active', 'success');
            return redirect()->route('states');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit state')) {
                return view('admin.errors.unauthorized');
            }
            $state = State::find($id);
            $state->status = 0;
            $state->save();

            Alert::toast('State has been inactive', 'error');
            return redirect()->route('states');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}