<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminList extends Component
{
    public $admins;

    public function mount()
    {
        $this->admins =Admin::where('type', '<>', 'vendor')->get();
    }

    public function toggleStatus($Id)
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('edit_admin'))
        {
            return view('admin.errors.unauthorized');
        }
        $admin = Admin::find($Id);
        $admin->status = $admin->status == 1 ? 0 : 1;
        $admin->update();
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.admin-list');
    }
}
