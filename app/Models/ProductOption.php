<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'title',
        'type'
    ];

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
