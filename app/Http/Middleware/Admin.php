<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
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
        $role = $request->user()->role;
        
        if('admin' === $role) {

            return $next($request);
        }

        return response()->json([
            'code' => 401,
            'message' => 'Not Authenticated',
        ], 401);
    }
}
