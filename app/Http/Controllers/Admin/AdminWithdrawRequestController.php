<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\AdminWithdrawRequest\AdminWithdrawRequest;
use Illuminate\Http\Request;
use PDO;

class AdminWithdrawRequestController extends Controller
{
    //
    public function index(){

        return view('admin.admin_withdraw_request.index')->withComponent(AdminWithdrawRequest::class);
    }
}