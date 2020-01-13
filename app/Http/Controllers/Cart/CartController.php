<?php


namespace App\Http\Controllers\Cart;


use App\Classes\Cart\Item;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Classes\Cart\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add($id, Request $request)
    {
        $product = Product::with(['images', 'options' => function ($query) {
            return $query->with(['values']);
        }, 'discounts' => function ($query) {
            return $query->where('active_from', '<', Carbon::now()->format('Y-m-d H:i:s'))->where('active_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->orderBy('active_from', 'ASC');
        }])->findOrFail($id);

        $cart = Cart::add($product, $request->input('quantity'), $request->input('option') ?? []);
        $request->session()->put('cart', json_encode($cart));
        return redirect()->back()->with('info', "<b>{$product->name}</b> has been added to your cart.");
    }

    public function index()
    {
        $cart = Cart::all();
        return view('cart.index', [
            'cart' => $cart
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'quantity.*' => 'required|numeric|min:0|max:50'
        ]);

        Cart::update($request->input('quantity'));
        return redirect()->back();
    }
}
