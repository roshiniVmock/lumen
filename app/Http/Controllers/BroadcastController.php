<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;

class BroadcastController extends Controller
{
    public function authenticate(Request $request)
    {
        $user = Auth::user();
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        // dd($request->user());
        // dump(Broadcast::auth($request));
        return Broadcast::auth($request);
    }
}
