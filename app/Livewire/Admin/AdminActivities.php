<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class AdminActivities extends Component
{
    public $adminId;
    public $filter='all';
    public $start_date;
    public $end_date;
    public $activityId;
    public $filteredUserActivity;
    public $isModalOpen = 0;


    public function mount($adminId, $filter='all', $start_date = null, $end_date = null)
    {
        $this->adminId = $adminId;
        $this->filter = $filter;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->filteredUserActivity=ActivityLog::where('user_id', $this->adminId)->get();
    }

    public function closeModal()
    {
        $this->activityId;
        // $this->resetInputFields();
    }

    public function deleteActivityLog(int $id)
    {
        $this->activityId = $id;
    }

    public function destroyActivityLog()
    {
        ActivityLog::findOrFail($this->activityId)->delete();
        session()->flash('message','Activity Log Deleted Successfully');
        $this->dispatch('close-modal');
        $this->mount($this->adminId, $filter='all', $start_date = null, $end_date = null);
    }



    public function render()
    {
        return view('livewire.admin.admin-activities', [
            'userActivity' => $this->filteredUserActivity,
        ]);
    }

    public function updateFilter()
    {
        $query = ActivityLog::where('user_id', $this->adminId);
        if ($this->filter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->filter === 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
        } elseif ($this->filter === 'this_week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'this_month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($this->filter === 'custom' && $this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        } elseif ($this->filter === 'all') {
            $query=ActivityLog::where('user_id', $this->adminId);
            // dump($query);
        }
        $this->filteredUserActivity = $query->get();
    }

}