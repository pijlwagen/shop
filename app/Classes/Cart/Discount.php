<?php

namespace App\Classes\Cart;

class Discount
{
    public $id;
    public $type;
    public $amount;
    public $active_from;
    public $active_until;

    public function __construct($discount)
    {
        $this->id = $discount->id;
        $this->type = $discount->type;
        $this->amount = $discount->amount;
        $this->active_from = $discount->active_from;
        $this->active_until = $discount->active_until;
    }
}
