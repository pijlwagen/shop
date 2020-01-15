<?php

namespace App;

use App\Models\Address;
use App\Models\Order;
use App\Models\Role;
use App\Models\UserAddress;
use App\Models\UserOrder;
use App\Models\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'blocked'
    ];

    protected $hidden = [
  'password', 'remember_token',
];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasManyThrough(Order::class, UserOrder::class, 'user_id', 'id', 'id', 'order_id');
    }

    public function roles()
    {
        return $this->hasManyThrough(Role::class, UserRole::class, 'user_id', 'id', 'id', 'role_id');
    }

    public function isAdmin()
    {
        return $this->roles()->get()->contains('name', 'Administrator');
    }

    public function address()
    {
        return $this->hasOneThrough(Address::class, UserAddress::class, 'user_id', 'id', 'id', 'address_id');
    }
}
