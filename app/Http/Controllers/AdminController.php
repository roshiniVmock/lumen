<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends Controller
{
    public function create_user(Request $request){
        JWTAuth::getToken();
        echo "Hello<br>";
        JWTAuth::parseToken()->authenticate();
        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role = $request->role;
        $user->created_by = $request->name;
        $user->deleted_by = "-";
        $user->save();
    }

    public function delete_user(Request $request){
        $user = User::where('name',$request->name);
        $user->deleted_by = auth()->name;
    }
}
