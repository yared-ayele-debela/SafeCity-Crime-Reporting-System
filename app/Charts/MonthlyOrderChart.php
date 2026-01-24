<?php
namespace App\Charts;

use App\Models\Order;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Carbon;

class MonthlyOrderChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $currentMonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $totalOrder = Order::all()->count();
        $before1MonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(1))
            ->count();
        $before2MonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(2))
            ->count();
        $before3MonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(3))
            ->count();
        $before4MonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(4))
            ->count();
        $before5MonthOrder = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth(5))
            ->count();

        $monthNames = [
            Carbon::now()->format('F'),
            Carbon::now()->subMonth(1)->format('F'),
            Carbon::now()->subMonth(2)->format('F'),
            Carbon::now()->subMonth(3)->format('F'),
            Carbon::now()->subMonth(4)->format('F'),
            Carbon::now()->subMonth(5)->format('F'),
        ];

        return $this->chart->lineChart()
            ->setTitle('Total Order ' . $totalOrder)
            ->setSubtitle('Current Month')
            ->addData('Orders', [$currentMonthOrder, $before1MonthOrder, $before2MonthOrder, $before3MonthOrder, $before4MonthOrder, $before5MonthOrder])
            ->setXAxis($monthNames);
    }
}
