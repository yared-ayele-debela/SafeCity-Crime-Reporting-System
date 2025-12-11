<?php

namespace App\Models;

use App\Models\Restaurant\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticable
{
    use Notifiable;

    protected $guard = "admin";

    protected $fillable = [
        'name',
        'type',
        'vendor_id',
        'mobile',
        'image',
        'status',
        'email',
        'created_at',
        'updated_at',
        'password',
    ];


    public function warehouses()
    {
        return $this->belongsToMany(WereHouses::class, 'manager_warehouse', 'manager_id', 'warehouse_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'admin__user__roles','admin_id', 'role_id');
    }

    public function hasPermissionByRole($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }


    public function vendorPersonal(){

        return $this->belongsTo('App\Models\Vendor','vendor_id');

    }
    public function vendorBusiness(){
        return $this->belongsTo('App\Models\VendorBussinessDetails','vendor_id');
    }

    public function vendorBank(){

        return $this->belongsTo('App\Models\VendorBankDetails','vendor_id');
    }


     public function Bank(){

        return $this->belongsTo('App\Models\VendorBankDetails','vendor_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hotel()
    {
        return $this->hasMany(Hotel::class);
    }
    public function restaurant()
    {
        return $this->hasMany(Restaurant::class);
    }

}