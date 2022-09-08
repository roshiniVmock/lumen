<?php

namespace App\Events;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use App\Broadcasting\TaskChannel;
// use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
class TaskAdded implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $task;

    public function __construct($task) {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        // return ['private-task'.$this->task->id,'tasks'];
        return new PrivateChannel('task.'.$this->task->assignee);
    }

    public function broadcastWith() {
        return [
        'title' => $this->task->title,
        'creator' => $this->task->creator,
        'assignee' => $this->task->assignee,
        'dueDate' => $this->task->dueDate,
        ];
    }
}
