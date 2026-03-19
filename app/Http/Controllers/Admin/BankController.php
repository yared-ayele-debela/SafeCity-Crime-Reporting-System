<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    //
    public function index()
    {
        $banks = Bank::latest()->get();
        return view('admin.bank.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
        ]);
        Bank::create($request->all());

          $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Bank Account', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        return back()->with('success', 'Bank information added.');
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->update($request->only('bank_name', 'account_number'));

           $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Bank Account', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        return back()->with('success', 'Bank information updated.');
    }

    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();

           $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Delete Bank Account', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        return back()->with('success', 'Bank information deleted.');
    }
}
