<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutOfStockRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorNotificationController extends Controller
{
    //
    public function index()
    {
        $admin=Auth::guard('admin')->user();

        $vendor=$admin->vendor_id;

        // dd($notifications);
        $newNotifications = OutOfStockRequest::with(['product', 'customer'])
            ->where('vendor_id', $vendor)
            ->where('is_new', 0)
            ->latest()
            ->get();

        $readNotifications = OutOfStockRequest::with(['product', 'customer'])
            ->where('vendor_id', $vendor)
            ->where('is_new', 1)
            ->latest()
            ->paginate(10);


        return view('admin.vendor.notifications.index', compact('newNotifications','readNotifications'));
    }

    public function markAsRead($id)
        {
            $notification = OutOfStockRequest::findOrFail($id);
            $notification->is_new=1;
            $notification->save();

            return back()->with('success', 'Notification marked as read.');
        }

}
