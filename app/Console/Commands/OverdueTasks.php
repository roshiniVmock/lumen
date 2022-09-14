<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class OverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdueTasks:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing the status of tasks when overdue';

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
            $date = Carbon::now()->toDateString();
            $tasks = Task::where('dueDate','<',$date);
            // Log::info($date);
            $tasks->each(function ($task){
                $task->update(["status"=>"OverDue"]);
                // Log::info($task->status);
            }); 
    }
}
