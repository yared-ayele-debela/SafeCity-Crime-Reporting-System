<?php

namespace App\Models\SafeCity;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action_type', 'description'];

    public function user()
    {
        return $this->belongsTo(related: User::class);
    }
}