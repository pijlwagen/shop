<?php

namespace App\Classes\Cart;

class Option
{
    public $id;
    public $value_id;
    public $title;
    public $value;
    public $increment;

    public function __construct($product, $option, $value)
    {
        $option = $product->options->find($option);
        $value = $option->values->find($value);

        $this->id = $option->id;
        $this->title = $option->title;
        $this->value_id = $value->id;
        $this->value = $value->value;
        $this->increment = $value->increment;
    }
}
