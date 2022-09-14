<?php

namespace App\Jobs;

class OverdueTasksJob extends Job
{
    protected $overDueTasks;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tasks)
    {
        $this->overDueTasks = $tasks;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($overDueTasks as $task){
            $task->update(["status"=>"OverDue"]);
        }
    }
}
