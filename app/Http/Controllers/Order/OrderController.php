<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function view($hash)
    {
        $order = Order::with(['payment', 'address', 'items' => function ($query) {
            return $query->with('options');
        }])->where('hash', $hash)->firstOrFail();

        return view('order.view', [
            'order' => $order
        ]);
    }
}
