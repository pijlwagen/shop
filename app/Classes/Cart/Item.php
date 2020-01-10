<?php

namespace App\Classes\Cart;

class Item
{
    public $id;
    public $slug;
    public $name;
    public $price;
    public $options= [];
    public $images = [];
    public $quantity = 1;
    public $discount;
    public $hash;

    public function __construct($product, $quantity, $options = [])
    {
        $this->id = $product->id;
        $this->slug = $product->slug;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->quantity = intval($quantity);
        $this->discount = $product->discounts->first() ? new Discount($product->discounts->first()) : null;
        $this->images = $product->images->map(function ($x) { return $x->path;})->toArray();

        foreach ($options as $key => $value) {
            array_push($this->options, new Option($product, $key, $value));
        }

        $this->makeHash();
    }

    function makeHash()
    {
        $this->hash = md5("$this->id" . implode('', array_map(function ($x) {return $x->value_id;}, $this->options)));
    }
}
