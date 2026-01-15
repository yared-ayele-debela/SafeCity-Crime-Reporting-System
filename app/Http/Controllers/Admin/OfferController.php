<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\OfferDetail;
use App\Livewire\OfferList;
use App\Livewire\OfferProduct;
use App\Models\Offer;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OfferController extends Controller
{
    //
    public function index(){
        try {

            return view('admin.offer.index')->withComponent(OfferProduct::class);
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }

    }
    public function product_offer($id){
        try {
            return view('admin.offer.offer_product',compact('id'))->withComponent(OfferList::class);
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }

    }

    public function detail($offerId)
    {

            return view('admin.offer.detail', ['offerId' => $offerId]);

    }
}