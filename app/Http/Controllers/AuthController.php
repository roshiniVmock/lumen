<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
class AuthController extends Controller
{
    
    public function signup(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
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
            // $this->sendWelcomeEmail($request);
            //return successful response
            return response()->json(['status' => 200,'user' => $user, 'message' => 'CREATED'], 201);

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
        // echo "{$request->deleted_by}";
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('email', $request->email)->first();
        if ($user -> deleted_by != "-") {
            return response()->json(['user' => 'Deleted',"deleted_by" => $user->deleted_by], 401);
        }
        // $user->fcm_token = $token;
        // echo $user->fcm_token;
        $user->save();
        return $this->respondWithToken($token,$user);
    }
    public function forgot_password(Request $request)
    {
        // echo "I am in forgot-password";
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return response()->json(['status' => $status]);
    }
    public function reset_password(Request $request)
    {
        // echo "Hello";
        $this->validate($request, [
            'token' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();
     
        return response()->json(['status' => 200]);
    }
    public function emailRequestVerification(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
        ]);
        JWTAuth::getToken();
        JWTAuth::parseToken()->authenticate();
        if ( $request->user()->hasVerifiedEmail() ) {
            return response()->json(['status' => 201,'msg'=>'Email address '.$request->user()->getEmailForVerification().' is already verified.']);
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
            return response()->json(['status' => 201,'msg'=>'Email address '.$request->user()->getEmailForVerification().' is already verified.']);
        }
        $request->user()->markEmailAsVerified();
        return response()->json([
            'status'=>200,
            'msg'=> 'Email address '. $request->user()->email.' successfully verified.']);
    }

}
