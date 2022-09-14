<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Task;
use App\Notifications\DailyReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TaskController;
class ReminderDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily reminder of upcoming tasks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        $users->each(function ($user){
            $tasks = Task::where('assignee',$user->name);
            $date = Carbon::now();
            $nextdate = Carbon::now();
            $nextdate = $nextdate->addDays(7)->toDateString();
            // Log::info($date);
            // Log::info($nextdate);
            $tasks = $tasks
                     ->where('dueDate','<=',$nextdate)
                     ->where('dueDate','>=',$date)->count();
            // Log::info($tasks);
            TaskController::createNotif(
                $user->name,
                "Upcoming Tasks",
                "You have ".strval($tasks)." upcoming tasks.",
                "Assignee",
            );
            $user->notify(new DailyReminder($tasks,$user));
        });
    }
}
