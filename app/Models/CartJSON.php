<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CartJSON extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'hash';

    protected $fillable = [
        'hash',
        'payload'
    ];

    public $incrementing = false;
}
