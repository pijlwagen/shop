<?php


namespace App\Http\Controllers\Admin\Order;


use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['payment', 'items'])->where('status', 0)->paginate($request->get('max') ?? 25);
        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }

    public function edit($hash, Request $request)
    {
        $order = Order::with(['payment', 'items' => function ($query) {
            $query->with(['options']);
        }, 'address'])->where('hash', $hash)->firstOrFail();
        return view('admin.orders.edit', [
            'order' => $order
        ]);
    }

    public function search(Request $request)
    {
        $orders = Order::with(['payment', 'items'])->where('orders.hash', $request->query('search'))->orWhere('orders.id', $request->query('search'))->limit(50)->get();
        return view('snippets.order-table', [
            'orders' => $orders
        ]);
    }
}
