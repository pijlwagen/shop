<?php


namespace App\Http\Controllers\Admin\Order;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Shipper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['payment', 'items', 'status'])->paginate($request->get('max') ?? 25);
        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }

    public function edit($hash, Request $request)
    {
        $order = Order::with(['payment', 'status', 'items' => function ($query) {
            $query->with(['options']);
        }, 'address'])->where('hash', $hash)->firstOrFail();

        $shippers = Shipper::all();

        return view('admin.orders.edit', [
            'order' => $order,
            'shippers' => $shippers
        ]);
    }

    public function update($hash, Request $request)
    {
        $order = Order::with(['payment', 'status', 'items', 'address'])->where('hash', $hash)->firstOrFail();

        if ($request->input('show-tracking')) {
            if ($order->status) {
                $order->status()->update([
                    'shipper_id' => $request->input('service'),
                    'status' => $request->input('status'),
                    'code' => $request->input('tracking')
                ]);
            } else {
                OrderStatus::create([
                    'order_id' => $order->id,
                    'shipper_id' => $request->input('service'),
                    'status' => $request->input('status'),
                    'code' => $request->input('tracking')
                ]);
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Successfully updated order');
    }

    public function search(Request $request)
    {
        $orders = Order::with(['payment', 'items'])->where('orders.hash', $request->query('search'))->orWhere('orders.id', $request->query('search'))->limit(50)->get();
        return view('snippets.order-table', [
            'orders' => $orders
        ]);
    }
}
