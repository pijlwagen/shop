<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'address_id',
        'phone',
        'email',
        'status',
        'hash'
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
