<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;
    protected $table="appsettings";
    protected $fillable=[
        'application_title',
        'address',
        'email_address',
        'phone_no',
        'favicon',
        'logo',
        'footer_text','created_at','updated_at'
    ];
}
