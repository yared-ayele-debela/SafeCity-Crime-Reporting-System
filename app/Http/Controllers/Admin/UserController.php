<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //
    public function users()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_users')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();
            $users = User::get()->toArray();
            return view('admin.users.allusers', compact('appsettings', 'users'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function adduser()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_user')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::all()->where('status', 1);
            $state = State::all()->where('status', 1);
            $country = Country::all()->where('status', 1);
            $appsettings = AppSetting::all()->toArray();
            return view('admin.users.adduser', compact('appsettings', 'city', 'state', 'country'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function store_user(Request $request)
    {
        try {
            if (!$request->method('post')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $this->validate($request, [
                'name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'pincode' => 'required',
                'country' => 'required',
                'email' => 'email',
                'mobile' => 'required',
            ]);

            $userWithEmail = User::where('email', $request->input('email'))->count();
            if ($userWithEmail > 0) {
                Alert::toast('User with this email already exists', 'error');
                return redirect()->back();
            }

            $userWithMobile = User::where('mobile', $request->input('mobile'))->count();
            if ($userWithMobile > 0) {
                Alert::toast('User with this mobile number already exists', 'error');
                return redirect()->back();
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            $user->country = $request->input('country');
            $user->pincode = $request->input('pincode');
            $user->save();
            
             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add User', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('User has been created successfully', 'success');
            return redirect('admin/users');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
        catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit_user($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_user')) {
                return view('admin.errors.unauthorized');
            }
            $city = City::all()->where('status', 1);
            $state = State::all()->where('status', 1);
            $country = Country::all()->where('status', 1);

            $appsettings = AppSetting::all()->toArray();
            $users = User::find($id);
            return view('admin.users.edituser', compact('users', 'appsettings', 'city', 'state', 'country'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update_user(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_user')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->method('put')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $this->validate($request, [
                'name' => 'required',
                'address' => 'required',
                'email' => 'email',
            ]);

            $user = User::find($request->input('id'));
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            $user->country = $request->input('country');
            $user->pincode = $request->input('pincode');
            $user->update();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update User', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('User has been updated successfully', 'success');
            return redirect('admin/users');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();

        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function destory($user_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_user')) {
                return view('admin.errors.unauthorized');
            }

            $user = User::find($user_id);
            $user->delete();

             $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
            ActivityLogger::log( 'Delete User', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('User has been deleted', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($user_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_user')) {
                return view('admin.errors.unauthorized');
            }
            $user = User::find($user_id);
            $user->status = 1;
            $user->update();

            Alert::toast('User status is set to active', 'success');
            return redirect('admin/users');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($user_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_user')) {
                return view('admin.errors.unauthorized');
            }
            $user = User::find($user_id);
            $user->status = 0;
            $user->update();

            Alert::toast('User status is set to inactive', 'error');
            return redirect('admin/users');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, $id)
    {
        // dd($request->password);
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update User Password', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        Alert::toast('User password updated successfully!', 'success');
        return redirect()->back();
    }

}