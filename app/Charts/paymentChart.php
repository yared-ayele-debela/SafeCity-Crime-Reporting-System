<?php

namespace App\Charts;

use App\Models\Order;
use Illuminate\Support\Carbon;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class paymentChart
{
    protected $pay;

    public function __construct(LarapexChart $pay)
    {
        $this->pay = $pay;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $paid = Order::where('order_status', 'Shipped')->count();
        $new = Order::where('order_status', 'New')->count();
        $delivered = Order::where('order_status', 'Delivered')->count();
        $pending = Order::where('order_status', 'Pending')->count();

        $paidl = Order::where('order_status', 'Shipped')->get()->toArray();
        $newl = Order::where('order_status', 'New')->get()->toArray();
        $deliveredl = Order::where('order_status', 'Delivered')->get()->toArray();
        $pendingl = Order::where('order_status', 'Pending')->get()->toArray();

        return $this->pay->barChart()
            ->setTitle('Orders Status Report')
            ->setSubtitle('All Orders')
            ->setLabels(['Shipped', 'New', 'Delivered', 'Pending'])
            ->addData('Orders', [$paid, $new, $delivered, $pending])
            ->setXAxis($paidl,$newl,$deliveredl,$pendingl);

    }
}
