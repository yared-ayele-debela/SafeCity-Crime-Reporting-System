<?php

namespace App\Livewire;

use App\Models\Tax;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaxComponent extends Component
{
    public $taxes, $taxname, $percentage, $tax_id;
    public $isModalOpen = 0;
    public $isDeleteModalOpen = false;


    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function openDeleteModal($id)
    {
        $this->tax_id = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
    }
    private function resetInputFields()
    {
        $this->taxname = '';
        $this->percentage = '';
        $this->tax_id = '';
    }

    public function store()
    {
        $this->validate([
            'taxname' => 'required',
            'percentage' => 'required|numeric',
        ]);

        Tax::updateOrCreate(['id' => $this->tax_id], [
            'taxname' => $this->taxname,
            'percentage' => $this->percentage,
        ]);

          $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Tax', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        session()->flash('message', $this->tax_id ? 'Tax updated successfully.' : 'Tax created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tax = Tax::findOrFail($id);
        $this->tax_id = $id;
        $this->taxname = $tax->taxname;
        $this->percentage = $tax->percentage;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->tax_id = $id;
        $this->openDeleteModal($id);
    }

    public function delete()
    {
        Tax::find($this->tax_id)->delete();

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Tax', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        
        session()->flash('message', 'Tax deleted successfully.');
        $this->closeDeleteModal();
    }

    public function update_status($id){
        $tax= Tax::find($id);
        $tax->status = $tax->status == 1 ? 0 : 1;
        $tax->save();

    }
    public function render()
    {
        $this->taxes = Tax::all();
        return view('livewire.tax-component');
    }
}