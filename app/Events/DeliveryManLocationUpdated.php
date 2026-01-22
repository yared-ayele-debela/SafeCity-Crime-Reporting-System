<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DeliveryManLocationUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $deliveryManId;
    public $latitude;
    public $longitude;

    public function __construct($deliveryManId, $latitude, $longitude)
    {
        $this->deliveryManId = $deliveryManId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function broadcastOn()
    {
        return new Channel('delivery-locations');
    }

    public function broadcastAs()
    {
        return 'location.updated';
    }
}
