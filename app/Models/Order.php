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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    function status()
    {
        switch ($this->status) {
            case 0:
                return 'Unprocessed';
            case 1:
                return 'Processed';
            case 2:
                return 'Shipped';
            case 3:
                return 'Delivered';
        }
    }
}
