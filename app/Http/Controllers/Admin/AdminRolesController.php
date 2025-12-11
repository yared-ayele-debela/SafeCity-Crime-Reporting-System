<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AppSetting;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminRolesController extends Controller
{
    public function edit(Admin $user)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('asgsign_role')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();

            $roles = Roles::all();
            return view('admin.role_and_permissions.admin_roles.edit', compact('user', 'appsettings', 'roles'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request, Admin $user)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('asgsign_role')) {
                return view('admin.errors.unauthorized');
            }

            $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);
            $user->roles()->sync($request->roles);

            return redirect()->route('users.index')->with('success', 'Roles assigned to user successfully.');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
