<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\Admin\AdminActivities;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    //
    public function activity_log($id){

        $admin=Admin::findOrFail($id);

        return view('admin.admin.activity.index',compact('admin'));
    }


}