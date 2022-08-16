<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
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
    public function forgot_password(Request $request)
    {
        $request->user()->sendPasswordResetNotification();
        
        return response()->json('Reset password link sent to '. Auth::user()->email);
    }
    public function reset_password(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|confirmed',
        ]);
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        if ( ! $request->user() ) {
            return response()->json('Invalid token', 401);
        }
        try
        {
            $user = User::where('name',$request->name)->first()->update(['password' => $request->password]);
        }
        catch (Exception $e)
        {
            return response()->json(['password' => 'Error while resetting passsword, please try again'], 401);
        }
        return response()->json('Password reset Succesfully');
    }
    public function emailRequestVerification(Request $request)
    {
        // return $request->all();
        if ( $request->user()->hasVerifiedEmail() ) {
            return response()->json('Email address is already verified.');
        }
        
        $request->user()->sendEmailVerificationNotification();
        
        return response()->json('Email request verification sent to '. Auth::user()->email);
    }
    public function emailVerify(Request $request)
    {
        $this->validate($request, [
        'token' => 'required|string',
        ]);
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        if ( ! $request->user() ) {
                return response()->json('Invalid token', 401);
            }
            
        if ( $request->user()->hasVerifiedEmail() ) {
            return response()->json('Email address '.$request->user()->getEmailForVerification().' is already verified.');
        }
        $request->user()->markEmailAsVerified();
        return response()->json('Email address '. $request->user()->email.' successfully verified.');
    }

}
