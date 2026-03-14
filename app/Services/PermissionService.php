<?php

// app/Services/PermissionService.php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermissionService
{
    public function checkAdminPermission(string $permission): bool
    {
        $user = Auth::guard('admin')->user();
        return $user && $user->hasPermissionByRole($permission);
    }
}