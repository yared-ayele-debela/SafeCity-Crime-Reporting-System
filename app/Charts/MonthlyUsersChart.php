<?php

namespace App\Charts;

use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $getUserCity = User::select(DB::raw('IFNULL(city, "Unknown") as city'), DB::raw('count(city) as count'))
        ->groupBy('city')
        ->get();

        $cityCounts = $getUserCity->pluck('count')->toArray();
        $cityLabels = $getUserCity->pluck('city')->toArray();

    return $this->chart->pieChart()
        ->setTitle('User Distribution by City')
        ->setLabels($cityLabels)
        ->addData($cityCounts);
    }
}
