<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $table = 'order_item_options';

    protected $fillable = [
        'order_item_id',
        'name',
        'value',
        'increment'
    ];

    public $incrementing = false;
    public $timestamps = false;
}
