<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'first_name',
        'last_name',
        'city',
        'zip',
        'address',
        'address_extra',
        'country',
        'type',
        'hash',
    ];


}
