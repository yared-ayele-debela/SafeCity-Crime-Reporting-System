<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;

class OfferList extends Component
{
    public $productId;
    public $offers = [];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->offers = Offer::with('user', 'product')
        ->where('product_id', $productId)
        ->orderBy('created_at', 'desc')
        ->get();    }


    public function render()
    {
        return view('livewire.offer-list',[
            'offers' => $this->offers,
        ]);
    }
}
