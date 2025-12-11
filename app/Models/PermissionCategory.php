<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory;
    protected $table="permission_categories";

    public function permissions()
    {
    return $this->hasMany(Permissions::class, 'category_id'); // Assuming 'category_id' is the actual

    }
}
