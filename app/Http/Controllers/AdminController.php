<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends Controller
{

    public function delete_user(Request $request){
        /**
         * Deleting of a user by Admin
         */
        $user = User::where('name',$request->name)->update(["deleted_by" => $request->deleted_by]);

    }
}
