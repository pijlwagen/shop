<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, \Closure $next)
    {
        $user = Auth::user();
        if (!$user->roles()->get()->contains('name', 'Administrator')) return redirect('/')->with('danger', 'You must be an Administrator to access that.');
        return $next($request);
    }
}
