<?php

namespace App\Http\Middleware;

use Closure;
use \Tymon\JWTAuth\Facades\JWTAuth;

class AdminUser
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
        // Pre-Middleware Action
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        if($request->user()->role !== "Admin"){
            return response()->json(["status"=>403,"msg"=>"Unauthorized"],403);
        }
        
        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
