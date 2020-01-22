<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{

    public function get($id)
    {
        $order = Order::with(['address', 'payment'])->find($id);
        if (!$order) return response()->json(['status' => 404, 'message' => 'order not found'], 404);
        return response()->json($order);
    }
}
