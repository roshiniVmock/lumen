<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $model;

	public function __construct(User $model)
	{
		$this->model = $model;
	}

    public function create(Request $request){
        // if (User::where('email', '=', $request->email)->count() > 0) {
        //     return "email id already exists";
        // }
        // if (User::where('name', '=', $request->name)->count() > 0) {
        //     return "username already exists";
        // }
        
        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role = $request->role;
        $user->created_by = $request->created_by;
        $user->deleted_by = "-";
        $user->save();
        
        // return $request->all();
    }
    public function list(Request $request){
        $builder = $this->model;

		if($request->has('id')) {
			$builder = $builder->where('id', $request->input('id'));
		}

		if($request->has('name')) {
			$builder = $builder->where('name', 'LIKE', '%'.$request->input('name').'%');
		}

		if($request->has('email')) {
			$builder = $builder->where('email', 'LIKE', '%'.$request->input('email').'%');
		}

		if($request->has('role')) {
			$builder = $builder->where('role', $request->input('role'));
		}
        if($request->has('created_by')){
            $builder = $builder->whereIn('created_by',$request->input('created_by'));
        }

		$users = $builder->get();
        return response()->json($users);
    }
    
    
}
