<?php

namespace App\Livewire;

use App\Models\ShippingZone;
use App\Models\State;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShipppingZone extends Component
{

    public $ShippingZones, $name, $regions,$ShippingZoneId,$selectedShippingZone;

    public function mount()
    {
        $this->resetInputFields();
        $this->ShippingZones = ShippingZone::with('states')->get();
    }
    public function resetInputFields()
    {
        $this->name = '';
        $this->regions = '';
        $this->ShippingZoneId = null;
    }


    public function closeModal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'regions' => 'required',
        ]);

        ShippingZone::create([
            'name' => $this->name,
            'regions' => $this->regions,
        ]);
         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Shipping Zone', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message','Shipping Zone Saved Successfully.');
        $this->dispatch('close-modal');
        $this->resetInputFields();
        $this->mount();

    }


    public function update()
    {
        $this->validate([
            'name' => 'required',
            'regions' => 'required',
        ]);

        $this->selectedShippingZone->update([
            'name' => $this->name,
            'regions' => $this->regions,
        ]);

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Update Shipping Zone', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message','Shipping Zone Updated Successfully.');
        $this->dispatch('close-modal');
        $this->resetInputFields();
        $this->mount();
    }

    public function edit($id)
    {
        $this->selectedShippingZone = ShippingZone::findOrFail($id);
        $this->ShippingZoneId = $id;
        $this->name = $this->selectedShippingZone->name;
        $this->regions = $this->selectedShippingZone->regions;


    }

    public function deleteShippingZone(int $id)
    {
        $this->ShippingZoneId = $id;
    }

    public function destroyShippingZone()
    {
        ShippingZone::findOrFail($this->ShippingZoneId)->delete();
         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Shipping Zone', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message','Shipping Zone Deleted Successfully');
        $this->dispatch('close-modal');
        $this->mount();
    }

    public function update_status($id){
        $shipping_zone= ShippingZone::find($id);
        $shipping_zone->status = $shipping_zone->status == 1 ? 0 : 1;
        $shipping_zone->update();
        $this->mount();
    }

    public function render()
    {
        $states= State::where('status',1)->get();
        return view('livewire.shippping-zone',[
            'states'=>$states,
            'ShippingZones'=> $this->ShippingZones
        ]);
    }
}