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

    public function create_user(Request $request)
    {
        /**
         * Creating a user by another user
         * Called during the session of a user
         */
        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->role = $request->role;
        $user->created_by = $request->created_by;
        $user->deleted_by = "-";
        $user->save();
        
    }
    public function list(Request $request){
        /**
         * Sorting and Filtering of the users 
         */
        $builder = $this->model;
		if($request->has('id')) {
            if (is_string($request->input('id'))){
                $builder = $builder->where('id', $request->input('id'));
            }
		}

		if($request->has('name')) {
            if (is_string($request->input('name'))){
                $builder = $builder->where('name', 'LIKE', '%'.$request->input('name').'%');
            }
		}
		if($request->has('email')) {
            if (is_string($request->input('email'))){
                $builder = $builder->where('email', 'LIKE', '%'.$request->input('email').'%');
            }
		}

		if($request->has('role')) {
            if (is_string($request->input('role'))){
                $builder = $builder->where('role', 'LIKE','%'.$request->input('role').'%.');
            }
		}
        if($request->has('created_by')){
            if (is_string($request->input('created_by'))){
                $builder = $builder->where('created_by','LIKE','%'.$request->input('created_by').'%');
            }
        }

        if($request->has('sort_by')){
            if($request->has('order')){
                $builder = $builder->orderBy($request->input('sort_by'),$request->input('order'));
            }
            else{
                $builder = $builder->orderBy($request->input('sort_by'),'ASC');
            }
        }

		$users = $builder->get();
        return response()->json($users);
    }
    
    
}
