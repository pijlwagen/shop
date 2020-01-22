<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Api
{
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->header('X-Token-Auth') || $request->header('X-Token-Auth') !== 'B2Y84kvluKAfa9DeJECW9unzkU57zDVH') return response()->json([
            'status' => 403,
            'message' => 'Missing API key, or API key is invalid'
        ]);
        return $next($request);
    }
}
