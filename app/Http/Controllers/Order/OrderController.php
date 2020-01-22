<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipper;

class OrderController extends Controller
{
    public function view($hash)
    {
        $order = Order::with(['payment', 'address', 'items' => function ($query) {
            return $query->with('options');
        }, 'status' => function ($query) {
            $query->with('shipper');
        }])->where('hash', $hash)->firstOrFail();

        return view('order.view', [
            'order' => $order,
        ]);
    }
}
