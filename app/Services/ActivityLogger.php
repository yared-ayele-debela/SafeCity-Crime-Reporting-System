<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($activity, $description = null)
    {
        $user = Auth::guard('admin')->user();
        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'activity' => $activity,
                'description' => $description,
            ]);
        }
    }
}