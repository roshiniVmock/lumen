<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function signup(Request $request){
        if (User::where('email', '=', $request->email)->count() > 0) {
            return "email id already exists";
        }
        if (User::where('name', '=', $request->name)->count() > 0) {
            return "username already exists";
        }
        $user = new User();
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;

        $user->save();
        return $request->all();
    }
    // public function users(Request $request){

    //     $users = \App\Models\User::all();
    //     return $users;
    // }
    public function userlisting(Request $request){
        foreach (User::all() as $user) {
            echo $user->name."<br>";
            echo $user->email."<br>";
        }
    }
    public function sendmail(Request $request){

        $data = ['message' => 'This is a test!'];

        Mail::to('john@example.com')->send(new Mail\TestEmail($data));
    }
    
    
}
