<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use Livewire\WithPagination;

class MainDashboard extends Component
{
    use WithPagination;


    public function render()
    {
        return view('livewire.dashboard.main-dashboard');
    }
}