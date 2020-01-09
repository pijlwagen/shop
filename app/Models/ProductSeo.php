<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductSeo extends Model
{
    protected $table = 'product_seo';

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'keywords',
        'image'
    ];

    public $incrementing = false;
    public $timestamps = false;
}
