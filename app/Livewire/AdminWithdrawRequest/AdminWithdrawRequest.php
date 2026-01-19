<?php

namespace App\Livewire\AdminWithdrawRequest;

use App\Models\WithdrawalRequest;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AdminWithdrawRequest extends Component
{
    use WithFileUploads;

    public $requests;
    public $receipt;
    public $description;
    public $selectedRequest;

    public $isModalOpen = false;
    public $isRejectModalOpen = false;



    public function mount()
    {
        $this->fetchRequests();
    }

    public function fetchRequests()
    {
        $this->requests = WithdrawalRequest::with('vendor')->latest()->get();
    }

    public function approveRequest($id)
    {
        $this->selectedRequest = WithdrawalRequest::findOrFail($id);
        $this->reset(['description', 'receipt']);
        $this->openModal();


    }

    public function confirmApproveRequest()
    {
        $this->validate([
            'receipt' => 'required|image|max:1024', // 1MB Max
            'description' => 'required|string|max:255',
        ]);

        $filePath = $this->receipt->store('receipts', 'public');

        $this->selectedRequest->update([
            'status' => 'approved',
            'description' => $this->description,
            'receipt' => $filePath,
        ]);

        $this->fetchRequests();
        session()->flash('success', 'Withdrawal request approved.');
        $this->closeModal();
        $this->mount();

    }

    public function rejectRequest($id)
    {
        $this->selectedRequest = WithdrawalRequest::findOrFail($id);
        $this->reset(['description']);
        $this->openRejectModal();

    }

    public function confirmRejectRequest()
    {
        $this->validate([
            'description' => 'required|string|max:255',
        ]);

        $this->selectedRequest->update([
            'status' => 'rejected',
            'description' => $this->description,
        ]);

        $this->fetchRequests();
        session()->flash('success', 'Withdrawal request rejected.');
        $this->closeRejectModal();

    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function openRejectModal()
    {
        $this->isRejectModalOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectModalOpen = false;
    }


    public function render()
    {
        return view('livewire.admin-withdraw-request.admin-withdraw-request');
    }
}