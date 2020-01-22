<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';

    protected $fillable = [
        'order_id',
        'shipper_id',
        'status',
        'code',
    ];

    public $timestamps = false;

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function text()
    {
        switch ($this->status) {
            case 1:
                return 'Processed';
            case 2:
                return 'Shipped';
            case 3:
                return 'Delivered';
            case 4:
                return 'Cancelled';
            case 5:
                return 'Returned';
            case 6:
                return 'Refunded';
        }
    }
}
