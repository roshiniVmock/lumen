<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\TaskChannel;
use App\Models\Task;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('task.{assignee}', function ($user, $assignee) {
    // return true;
    // dd("broadcasting..");
    return (string) $user->name == (string) $assignee;
});
// Broadcast::channel('tasks',TaskChannel::class);
