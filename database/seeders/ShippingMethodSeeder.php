<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingMethods = [
            [
                'name' => 'Standard Shipping',
                'description' => 'Delivers in 5-7 business days',
                'base_cost' => 5.00,
                'per_kg_rate' => 0.50,
            ],
            [
                'name' => 'Express Shipping',
                'description' => 'Delivers in 2-3 business days',
                'base_cost' => 15.00,
                'per_kg_rate' => 1.00,
            ],
            [
                'name' => 'Next Day Delivery',
                'description' => 'Delivers by next business day',
                'base_cost' => 25.00,
                'per_kg_rate' => 2.00,
            ],
            [
                'name' => 'International Economy',
                'description' => 'Delivers in 10-14 business days',
                'base_cost' => 20.00,
                'per_kg_rate' => 5.00,
            ],
            [
                'name' => 'International Priority',
                'description' => 'Delivers in 3-5 business days',
                'base_cost' => 50.00,
                'per_kg_rate' => 10.00,
            ],
            [
                'name' => 'Local Pickup',
                'description' => 'Pickup from our local store',
                'base_cost' => 0.00,
                'per_kg_rate' => 0.00,
            ],
            [
                'name' => 'Courier Service',
                'description' => 'Same-day delivery within city',
                'base_cost' => 30.00,
                'per_kg_rate' => 3.00,
            ],
            [
                'name' => 'Drone Delivery',
                'description' => 'Fast and secure delivery by drone',
                'base_cost' => 40.00,
                'per_kg_rate' => 5.00,
            ],
            [
                'name' => 'Bulk Freight',
                'description' => 'Ideal for large shipments',
                'base_cost' => 100.00,
                'per_kg_rate' => 1.00,
            ],
            [
                'name' => 'Economy Air',
                'description' => 'Affordable air shipping',
                'base_cost' => 25.00,
                'per_kg_rate' => 2.50,
            ],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::create($method);
        }
    }
}
