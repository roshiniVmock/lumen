<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
class TaskController extends Controller
{
    protected $model;

	public function __construct(User $model)
	{
		$this->model = $model;
	}

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
        ]);
        $task = new Task();
        $task->title = $request->title;
        $task->assignee = $request->assignee;
        $task->creator = $request->creator;
        $task->assignedDate = $request->assignDate;
        $task->dueDate = $request->dueDate;
        $task->description = $request->description;
        $task->status = 'Assigned';
        $task->save();
    }

    public function update(Request $request)
    {
        $task = Task::where('title',$request->preTitle);
        if($request->has('title')){
            $task->update(["title" => $request->title]);
        }

        if($request->has('description')){
            $task->update(["description"=>$request->description]);
        }

        if($request->has('dueDate')){
            $task->update(["dueDate"=>$request->dueDate]);
        }
    }

    public function updateStatus(Request $request)
    {
        $task = Task::where('title',$request->title)->update(['status'=>$request->status]);
    }

    public function assignedList(Request $request)
    {
        // $id = (User::where('email',$request->email))->get(['id']);
        // $builder = $this->model;
        // $assignedTasks = $builder->find($id);
        // dd($assignedTasks->tasks);
        // $createdTasks = $buidler->find(1)->createTasks;
        $role = User::where('email',$request->email)->get(['role']);
        // $tasks = $builder->find(1)->(assignedTasks or createTasks);
        $assignedTasks = Task::where('assignee',$request->name);
        if ($role === 'Admin'){
            $assignedTasks = Task::all();
        } 
        if($request->has('keywords') && $request->keywords != []){
            $words = $request->keywords;
            $assignedTasks = $assignedTasks
            ->where(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('assignee', 'like', '%' . $term . '%');
                }
            })
            ->orWhere(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('title', 'like', '%' . $term . '%');
                }
            })
            ->orWhere(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('description','LIKE', '%'.$term.'%');
                }
            });
        }
        if($request->has('startDate') && $request->startDate !=''){
            $assignedTasks = $assignedTasks->whereDate('dueDate','>=',$request->startDate);
        }
        if($request->has('endDate') && $request->endDate != ''){
            $assignedTasks = $assignedTasks->whereDate('dueDate','<=',$request->endDate);
        }
        if($request->has('status') && $request->status != ''){
            $assignedTasks = $assignedTasks->where('status','LIKE','%'.$request->status.'%');
        }
        return response()->json($assignedTasks->get());
    }
    public function createdList(Request $request)
    {
        $role = User::where('email',$request->email)->get(['role']);
        // $builder = $this->model;
        // $createdTasks = $builder->where('email',$request->email);
        // //->tasks();
        // // ->createTasks;
        // dd($request);
        // // dd(Task::all());
        // dd($request);
        // echo $request;
        $createdTasks = Task::where('creator',$request->name);
        if ($role === 'Admin'){
            $createdTasks = Task::all();
        }   
        if($request->has('keywords') && $request->keywords != []){
            $words = $request->keywords;
            $createdTasks = $createdTasks
            ->where(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('assignee', 'like', '%' . $term . '%');
                }
            })
            ->orWhere(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('title', 'like', '%' . $term . '%');
                }
            })
            ->orWhere(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('description','LIKE', '%'.$term.'%');
                }
            });
        }
        if($request->has('startDate') && $request->startDate !=''){
            $createdTasks = $createdTasks->whereDate('dueDate','>=',$request->startDate);
        }
        if($request->has('endDate') && $request->endDate != ''){
            $createdTasks = $createdTasks->whereDate('dueDate','<=',$request->endDate);
        }
        if($request->has('status') && $request->status != ''){
            $createdTasks = $createdTasks->where('status','LIKE','%'.$request->status.'%');
        }
        return response()->json($createdTasks->get());
    }
    public function upcoming(Request $request)
    {
        $upcomingTasks = Task::where('assignee',$request->name);
        $upcomingTasks = $upcomingTasks
                        ->where('dueDate','<=',$request->update)
                        ->where('dueDate','>=',$request->date);
        return response()->json($upcomingTasks->get());
    }
    public function overdue(Request $request)
    {
        $overdueTasks = Task::where('assignee',$request->name);
        $overdueTasks = $overdueTasks->where('dueDate','<',$request->date);
        return response()->json($overdueTasks->get());
    }
    public function complete(Request $request)
    {
        $completedTasks = Task::where('assignee',$request->name);
        $completedTasks = $completedTasks->where('status','Complete');
        return response()->json($completedTasks->get());
    }
    public function inProgress(Request $request)
    {
        $inProgressTasks = Task::where('assignee',$request->name);
        $inProgressTasks = $inProgressTasks->where('status','In-Progress');
        return response()->json($inProgressTasks->get());
    }
}
