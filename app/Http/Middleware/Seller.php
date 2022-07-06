<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $role = $request->user()->role;
        
        if('seller' === $role || 'admin' === $role) {

            return $next($request);
        }

        return response()->json([
            'code' => 401,
            'message' => 'Not Authenticated',
        ], 401);
    }
}
