<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CategorySeo extends Model
{
    protected $table = 'product_seo';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'keywords',
        'image'
    ];

    public $incrementing = false;
    public $timestamps = false;
}
