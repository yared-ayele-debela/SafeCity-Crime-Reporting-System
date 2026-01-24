<?php

namespace App\Charts;

use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Carbon;

class MonthlyUserRegisterChart
{
    protected $user;

    public function __construct(LarapexChart $user)
    {
        $this->user = $user;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $currentMonthOrder = User::whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->count();
        $before_1_month_user=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(1))->count();
        $before_2_month_user=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(2))->count();
        $before_3_month_user=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(3))->count();

        $monthNames = [
            Carbon::now()->format('F'),
            Carbon::now()->subMonth(1)->format('F'),
            Carbon::now()->subMonth(2)->format('F'),
            Carbon::now()->subMonth(3)->format('F'),
        ];
        return $this->user->barChart()
            ->setTitle('Users Registeration Report')
            ->setSubtitle('Current Month')
            ->addData('Users', [$currentMonthOrder,$before_1_month_user,$before_2_month_user,$before_3_month_user])
            ->setXAxis($monthNames);
    }
}
