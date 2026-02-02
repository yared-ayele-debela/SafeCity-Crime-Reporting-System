<?php

namespace App\Models\SafeCity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table="contact_messages";

        protected $fillable = ['name', 'email', 'subject', 'message'];

}