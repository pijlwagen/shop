<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'hash',
        'amount',
        'method',
        'order_id',
        'address_id'
    ];
}
