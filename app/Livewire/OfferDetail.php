<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;

class OfferDetail extends Component
{
    public $offerId;
    public $offer;
    public $status;


    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->loadOffer();
    }

    public function loadOffer()
    {
        $this->offer = Offer::find($this->offerId);
    }

    public function update_status()
    {
        $offer = Offer::find($this->offerId);
        $offer->status = $this->status;
        $offer->save();

        session()->flash('message','Offer Updated Successfully');
        $this->mount($this->offerId);
    }

    public function render()
    {

        return view('livewire.offer-detail');
    }
}