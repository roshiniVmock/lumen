<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function signup(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->role = 'Normal';
            $user->created_by = $user->name;
            $user->deleted_by = "-";
            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            echo $e;
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only(['email', 'password']);
        echo "{$request->deleted_by}";
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('name', $request->name)->first();
        if ($user -> deleted_by != "-") {
            return response()->json(['user' => 'Deleted',"deleted_by" => $user->deleted_by], 401);
        }
        // $user->fcm_token = $token;
        echo $user->fcm_token;
        $user->save();
        return $this->respondWithToken($token);
    }
    

}
