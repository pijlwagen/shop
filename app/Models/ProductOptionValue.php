<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $table = 'product_option_values';

    protected $fillable = [
        'product_option_id',
        'value',
        'disabled',
        'increment'
    ];
}
