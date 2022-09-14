<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use App\Events\TaskAdded;
use App\Events\TaskStatusChange;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Notifications\TaskCreated;
use App\Notifications\TaskStatusChanged;
use App\Notifications\EditedTask;
use App\Notifications\DeletedTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $model;

	public function __construct(User $model)
	{
		$this->model = $model;
	}

    public function createNotif($recipient, $title, $description, $recipientType)
    {
        $notif = new Notification();
        $notif->title = $title;
        $notif->description = $description;
        $notif->hasRead = false;
        $notif->recipient = $recipient;
        $notif->recipientType = $recipientType;
        $notif->save();
        // Log::info(Notification::all());
    }

    

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|unique:tasks',
        ]);
        $task = new Task();
        $task->title = $request->title;
        $task->assignee = $request->assignee;
        $task->creator = $request->creator;
        $task->assignedDate = $request->assignDate;
        $task->dueDate = $request->dueDate;
        $task->description = $request->description;
        $task->status = 'Assigned';
        // dispatch(new TaskAdded($task));
        $this->createNotif($task->assignee, $task->title, $task->title." Created ", "Assignee");
        $task->save();
        // return response($task,201);
        event(new TaskAdded($task));
        $user = User::where('name',$task->assignee)->first();
        $user->notify(new TaskCreated($task));
    }

    public function update(Request $request)
    {
        $task = Task::where('title',$request->prevTitle)->first();
        if($request->has('title') && $request->title != ''){
            $task->update(["title" => $request->title]);
        }

        if($request->has('description') && $request->description != ''){
            $task->update(["description"=>$request->description]);
        }

        if($request->has('dueDate') && $request->dueDate != ''){
            $task->update(["dueDate"=>$request->dueDate]);
        }
        $this->createNotif($task->assignee, $task->title, $request->prevTitle." Updated", "Assignee");
        event(new TaskUpdated($task));
        $user = User::where('name',$task->assignee)->first();
        $user->notify(new EditedTask($task));

    }

    public function updateStatus(Request $request)
    {
        $task = Task::where('title',$request->title)->first();
        $user = User::where('name',$task->creator)->first();
        $task->update(['status'=>$request->status]);
        if($request->status==="Deleted"){
            event(new TaskDeleted($task));
            $user = User::where('name',$task->assignee)->first();
            $user->notify(new DeletedTask($task));
            $this->createNotif($task->assignee, $task->title, $task->title." Deleted", "Assignee");
        }
        else{
            $this->createNotif($task->creator, $task->title, $task->title." Status Updated", "Creator");
            event(new TaskStatusChange($task));
            $user->notify(new TaskStatusChanged($task));
        }
    }

    public function assignedList(Request $request)
    {

        $role = User::where('email',$request->email)->get(['role']);
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
        if($request->has('creator') && $request->creator != []){
            $words = $request->creator;
            $assignedTasks = $assignedTasks
            ->where(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('creator', 'like', '%' . $term . '%');
                }
            });
        }
        if($request->has('startDate') && $request->startDate !=''){
            // dump(Carbon::now()->addDays(1)->toDateString(),$request->startDate);

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
        if($request->has('assignee') && $request->assignee != []){
            $words = $request->assignee;
            $createdTasks = $createdTasks
            ->where(function ($query) use ($words) {
                foreach ($words as $term) {
                    $query->orWhere('assignee', 'like', '%' . $term . '%');
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
                        ->where('dueDate','>=',$request->date)
                        ->orderBy('dueDate');
        return response()->json($upcomingTasks->get());
    }
    public function overdue(Request $request)
    {
        $date = Carbon::now()->toDateString();
        $overdueTasks = Task::where('assignee',$request->name);
        $overdueTasks = $overdueTasks->where('dueDate','<',$date)->orderBy('dueDate');
        return response()->json($overdueTasks->get());
    }
    public function complete(Request $request)
    {
        $completedTasks = Task::where('assignee',$request->name);
        $completedTasks = $completedTasks->where('status','Complete')->orderBy('dueDate');
        return response()->json($completedTasks->get());
    }
    public function inProgress(Request $request)
    {
        $inProgressTasks = Task::where('assignee',$request->name);
        $inProgressTasks = $inProgressTasks->where('status','In-Progress')->orderBy('dueDate');
        return response()->json($inProgressTasks->get());
    }
}
