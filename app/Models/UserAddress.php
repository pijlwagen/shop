<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'address_id'
    ];

    public $incrementing = false;
    public $timestamps = false;
}
