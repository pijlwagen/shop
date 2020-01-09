<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    protected $table = 'product_discounts';

    protected $fillable = [
        'product_id',
        'amount',
        'type',
        'active_from',
        'active_until'
    ];

    public $timestamps = false;
}
