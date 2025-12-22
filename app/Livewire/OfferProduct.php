<?php

namespace App\Livewire;

use App\Models\Offer;
use App\Models\Product;
use Livewire\Component;

class OfferProduct extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::whereHas('offers')->get();
    }

    public function redirectToOffers($productId)
    {
        return redirect()->to('admin/offer-products/'.$productId.'/offers');
    }


    public function render()
    {
        return view('livewire.offer-product',[
            'products' => $this->products,
        ]);
    }
}