<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AppSetting;
use App\Models\Roles;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_admin')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();

            $users = Admin::all();
            return view('admin.role_and_permissions.admins.index', compact('users', 'appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function assignRole(Admin $user)
    {
        try {
            $users = Auth::guard('admin')->user();
            if (!$users || !$users->hasPermissionByRole('assign_role')) {
                return view('admin.errors.unauthorized');
            }

            $roles = Roles::all();
            $appsettings = AppSetting::all()->toArray();

            return view('admin.role_and_permissions.admins.assing_role', compact('user', 'roles', 'appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function updateRole(Request $request, Admin $user)
    {
        if (!$request->isMethod('post')) {
            // Display an error or handle the incorrect request method
            Alert::toast('Invalid request method!', 'error');
            return redirect()->route('all-admins.index');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:admins,id',
        ]);

        try {
            $role = Roles::find($request->role_id);

            if (!$role) {
                Alert::toast('Role does not exist!', 'error');
                return redirect()->route('all-admins.index');
            }

            $admin = Admin::find($request->input('user_id'));

            if (!$admin) {
                Alert::toast('Admin does not exist!', 'error');
                return redirect()->route('all-admins.index');
            }

            $admin->type = $role->name;
            $admin->save();

            $admin->roles()->sync([$request->role_id]);



        $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Assign Role', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Role assigned to user successfully!', 'success');
            return redirect()->route('all-admins.index');
        } catch (\Exception $e) {
            Alert::toast('Something went wrong!', 'error');
            return redirect()->route('all-admins.index');
        }
    }
}
