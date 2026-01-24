<?php

namespace App\Charts;

use App\Models\Order;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class Order_Payment_StatusChart
{
    protected $payment;

    public function __construct(LarapexChart $payment)
    {
        $this->payment = $payment;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $order_status = Order::select('order_status', DB::raw('count(order_status) as count'))
        ->groupBy('order_status')
        ->get();

        $order_statusCounts = $order_status->pluck('count')->toArray();
        $order_statuscityLabels = $order_status->pluck('order_status')->toArray();

    return $this->payment->pieChart()
        ->setTitle('Order Payment Status')
        ->setLabels($order_statuscityLabels)
        ->addData($order_statusCounts);
    }
}
