<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        if ( !$user) {
            return response()->json([
                'status' => false,
                'msg' => 'Token is missing/invalid',
            ], 422);
        }
 
        return $next($request);
    }
}
