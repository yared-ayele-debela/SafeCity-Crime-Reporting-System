<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminLoginInToVendorController extends Controller
{
    //

    public function loginAsVendor(Request $request,$id)
    {
        $vendor=Admin::where('vendor_id',$id)->first();
        // dd($vendor);
        if(!Auth::guard('admin')->check() || Auth::guard('admin')->user()->type != 'Super Admin') {
            // dd("vendor");
            Alert::toast('Unauthorized action.','error');
            return redirect()->back();
        }
        $request->session()->put('admin_id', Auth::guard('admin')->user()->id);

          $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Admin login to Venodr Account', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        Auth::guard('admin')->logout();
        Auth::guard('admin')->login($vendor);


        return redirect()->route('maindashboard');
    }

    public function switchBackToAdmin(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $adminId = $request->session()->pull('admin_id');
            $admin = Admin::find($adminId);
            // dd($admin);

            Auth::guard('admin')->logout();
            Auth::guard('admin')->login($admin);

            return redirect()->route('maindashboard');
        }else{
            Alert::toast('Something is wrong','error');
            return redirect()->back();
        }
    }
}
