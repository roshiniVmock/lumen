<?php

namespace App\Http\Controllers;
use App\Mail\MyTestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use \Tymon\JWTAuth\Facades\JWTAuth;
class EmailController extends Controller
{
    public function sendEmail()
    {
        /** 
         * Store a receiver email address to a variable.
         */
        $user = User::find(1)->toArray();

        /**
         * Import the Mail class at the top of this page,
         * and call the to() method for passing the 
         * receiver email address.
         * 
         * Also, call the send() method to incloude the
         * HelloEmail class that contains the email template.
         */
        Mail::to($user['email'])->send(new MyTestMail);

        /**
         * Check if the email has been sent successfully, or not.
         * Return the appropriate message.
         */
        if (Mail::failures() != 0) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
    /**
  * Request an email verification email to be sent.
  *
  * @param  Request  $request
  * @return Response
  */
  public function emailRequestVerification(Request $request)
  {
    if ( $request->user()->hasVerifiedEmail() ) {
        return response()->json('Email address is already verified.');
    }
    
    $request->user()->sendEmailVerificationNotification();
    
    return response()->json('Email request verification sent to '. Auth::user()->email);
  }
    /**
     * Verify an email using email and token from email.
    *
    * @param  Request  $request
    * @return Response
    */
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