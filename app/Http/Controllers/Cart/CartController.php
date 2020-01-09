<?php


namespace App\Http\Controllers\Cart;


use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function add($id, Request $request)
    {
        $product = Product::with(['images', 'options' => function ($query) {
            return $query->with(['values']);
        }, 'discounts' => function ($query) {
            return $query->where('active_from', '<', Carbon::now()->format('Y-m-d H:i:s'))->where('active_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->orderBy('active_from', 'ASC');
        }])->findOrFail($id);
        $condition = null;
        $discount = $product->discounts->first();
        if ($discount) {
            switch ($discount->type) {
                case 'fixed':
                    $condition = new CartCondition([
                        'name' => 'SALE &euro;' . $discount->amount,
                        'type' => 'sale',
                        'value' => "-{$discount->amount}"
                    ]);
                    break;
                case 'percentage':
                    $condition = new CartCondition([
                        'name' => "SALE {$discount->amount}%",
                        'type' => 'sale',
                        'value' => "-{$discount->amount}%"
                    ]);
                    break;
                case 'free':
                    $condition = new CartCondition([
                        'name' => "SALE 100%",
                        'type' => 'sale',
                        'value' => "-100%"
                    ]);
                    break;
            }
        }
        dd($product->toArray());
        $item = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->input('quantity'),
            'attributes' => [],
        ];
        if ($condition) {

        }
        Cart::add();

        return redirect()->route('products.view', $product->slug)->with('success', 'Added to cart');
    }

    public function index()
    {
        $cart = Cart::getContent();
        return view('cart.index', [
            'cart' => $cart
        ]);
    }
}
