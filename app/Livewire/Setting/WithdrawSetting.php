<?php

namespace App\Livewire\Setting;

use App\Models\WithdrawSetting as ModelsWithdrawSetting;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WithdrawSetting extends Component
{
    public $withdrawals, $amount, $status, $withdrawal_id;
    public $isCreateModalOpen = false;
    public $isDeleteModalOpen = false;


    public function create()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function openCreateModal()
    {
        $this->isCreateModalOpen = true;
    }

    public function closeCreateModal()
    {
        $this->isCreateModalOpen = false;
    }

    public function openDeleteModal($id)
    {
        $this->withdrawal_id = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->amount = '';
        $this->withdrawal_id = '';
    }

    public function store()
    {
        $this->validate([
            'amount' => 'required|numeric',
        ]);

        ModelsWithdrawSetting::updateOrCreate(['id' => $this->withdrawal_id], [
            'amount' => $this->amount,
        ]);

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add withdraw setting', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message', $this->withdrawal_id ? 'Withdrawal updated successfully.' : 'Withdrawal created successfully.');

        $this->closeCreateModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $withdrawal = ModelsWithdrawSetting::findOrFail($id);
        $this->withdrawal_id = $id;
        $this->amount = $withdrawal->amount;

        $this->openCreateModal();
    }

    public function confirmDelete($id)
    {
        $this->withdrawal_id = $id;
        $this->openDeleteModal($id);
    }

    public function delete()
    {
        ModelsWithdrawSetting::find($this->withdrawal_id)->delete();
        
         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete withdraw setting', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        session()->flash('message', 'Withdrawal deleted successfully.');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $this->withdrawals = ModelsWithdrawSetting::all();
        return view('livewire.setting.withdraw-setting');
    }
}