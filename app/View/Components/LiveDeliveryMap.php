<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LiveDeliveryMap extends Component
{
    public $latitude;
    public $longitude;
    public $deliveryManId;
    public $destinationIcon;

    public function __construct($latitude, $longitude, $deliveryManId, $destinationIcon = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->deliveryManId = $deliveryManId;
        $this->destinationIcon = $destinationIcon ?? asset('restaurant_frontend/store.gif'); // fallback
    }


    public function render()
    {
        return view('components.live-delivery-map');
    }
}