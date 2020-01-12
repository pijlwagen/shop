<?php


namespace App\Http\Middleware;


use App\Classes\Cart\Cart;
use Illuminate\Http\Request;

class EmptyCart
{
    public function handle(Request $request, \Closure $next)
    {
        if (Cart::count() === 0) return redirect()->route('cart.index');
        return $next($request);
    }
}
