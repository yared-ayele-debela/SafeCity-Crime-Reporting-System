<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ApproveOrRejectWithdrawRequestController extends Controller
{
    public function index()
    {
        $requests = WithdrawalRequest::with('vendor')->where('status', 'pending')->get();
        return view('admin.withdrawal_requests.index', compact('requests'));
    }

    public function approveWithdrawalRequest($id)
    {
        $request = WithdrawalRequest::findOrFail($id);
        $request->update(['status' => 'approved']);

        Alert::toast('Withdrawal request approved.','success');
        return redirect()->route('admin.withdrawal_requests');
    }

    public function rejectWithdrawalRequest($id)
    {
        $request = WithdrawalRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);

        Alert::toast('Withdrawal request rejected.','success');
        return redirect()->route('admin.withdrawal_requests');
    }
}