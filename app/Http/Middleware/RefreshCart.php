<?php


namespace App\Http\Middleware;


use App\Classes\Cart\Cart;
use Illuminate\Http\Request;

class RefreshCart
{
    public function handle(Request $request, \Closure $next)
    {
        Cart::refresh();
        return $next($request);
    }
}
