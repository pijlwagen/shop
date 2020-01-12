<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'hidden',
        'image'
    ];

    public function seo()
    {
        return $this->hasOne(CategorySeo::class);
    }
}
