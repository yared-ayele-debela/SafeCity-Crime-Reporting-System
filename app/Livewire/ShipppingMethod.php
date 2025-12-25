<?php

namespace App\Livewire;

use App\Models\ShippingMethod;
use App\Models\ShippingMethodPrice;
use App\Models\ShippingZone;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShipppingMethod extends Component
{
    public $ShippingMethods,
     $selectedzone,
     $name,
     $description,
     $base_cost,
     $per_kg_rate,
     $ShippingMethodId,
     $selectedShippingMethod;
     public $prices = [];
     public $zones = [];
     public $isModalOpen = 0;

    public function mount()
    {
        $this->resetInputFields();
        $this->ShippingMethods = ShippingMethod::with('zones')->get();
        // dump($this->ShippingMethods);
    }
    public function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->base_cost = '';
        $this->per_kg_rate = '';
        $this->zones = '';
        $this->ShippingMethodId = null;
    }


    public function closeModal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_cost' => 'required|numeric',
            'per_kg_rate' => 'nullable|numeric',
            'zones' => 'exists:shipping_zones,id',
        ]);

        $shippingMethod = ShippingMethod::create([
            'name' => $this->name,
            'description' => $this->description,
            'base_cost' => $this->base_cost,
            'per_kg_rate' => $this->per_kg_rate,
        ]);

        foreach ($this->zones as $zoneId) {
            if (isset($this->prices[$zoneId])) {
                ShippingMethodPrice::create([
                    'shipping_method_id' => $shippingMethod->id,
                    'zone_id' => $zoneId,
                    'price' => $this->prices[$zoneId],
                ]);
            }
        }

        if(isset($this->zones)){
            $shippingMethod->zones()->attach($this->zones);
        }

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Shipping Method', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");



        session()->flash('message','Shipping Method Saved Successfully.');
        $this->dispatch('close-modal');
        $this->resetInputFields();
        $this->mount();

    }


    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_cost' => 'required|numeric',
            'per_kg_rate' => 'nullable|numeric',
            'zones' => 'array|exists:shipping_zones,id',
        ]);


        $shippingMethod = $this->selectedShippingMethod->update([
            'name' => $this->name,
            'description' => $this->description,
            'base_cost' => $this->base_cost,
            'per_kg_rate' => $this->per_kg_rate,
        ]);

        foreach ($this->zones as $zoneId) {
            if (isset($this->prices[$zoneId])) {
                $this->selectedShippingMethod->prices()->updateOrCreate([
                    'zone_id' => $zoneId,
                    'price' => $this->prices[$zoneId],
                ]);
            }
        }

        if(isset($this->zones)){
            $this->selectedShippingMethod->zones()->sync($this->zones);
        }

        
         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Update Shipping Method', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message','Shipping Method Updated Successfully.');
        $this->dispatch('close-modal');
        $this->resetInputFields();
        $this->mount();
    }

    public function edit($id)
    {
        $this->selectedShippingMethod = ShippingMethod::findOrFail($id);
        $this->ShippingMethodId = $id;
        $this->name = $this->selectedShippingMethod->name;
        $this->description = $this->selectedShippingMethod->description;
        $this->base_cost = $this->selectedShippingMethod->base_cost;
        $this->per_kg_rate = $this->selectedShippingMethod->per_kg_rate;
        $this->zones = $this->selectedShippingMethod->zones->pluck('id')->toArray();
        $this->loadPrices();

    }

    public function loadPrices()
    {
        foreach ($this->selectedShippingMethod->prices as $price) {
            $this->prices[$price->zone_id] = $price->price;
        }
    }

    public function deleteShippingMethod(int $id)
    {
        $this->ShippingMethodId = $id;
    }

    public function destroyShippingMethod()
    {
        ShippingMethod::findOrFail($this->ShippingMethodId)->delete();

        
         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Delete Shipping Method', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message','Shipping Method Deleted Successfully');
        $this->dispatch('close-modal');
        $this->mount();
    }

    public function update_status($id){
        $shipping_zone= ShippingMethod::find($id);
        $shipping_zone->status = $shipping_zone->status == 1 ? 0 : 1;
        $shipping_zone->update();
        $this->mount();
    }

    public function render()
    {
        return view('livewire.shippping-method',
        [
            'ShippingMethods'=> $this->ShippingMethods,
            'allZones'=>ShippingZone::all()
        ]);
    }
}