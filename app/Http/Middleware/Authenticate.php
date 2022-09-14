<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use \Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    
    public function __construct()
    {
        //
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        if (! $request->user()){
            return response()->json(["status"=>403,"msg"=>"Unauthorized"]);
        }
        return $next($request);
    }
}
