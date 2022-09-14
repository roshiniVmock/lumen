<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyReminder extends Notification
{
    use Queueable;
    public $uptasksCount,$user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($count,$user)
    {
        $this->uptasksCount = $count;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        $loginUrl = url("http://localhost:3000/assignedTasks");
        return (new MailMessage)
                    ->subject('Daily Reminder of Upcoming Tasks')
                    ->greeting('Hello '.$this->user->name.',')
                    ->line('You have '.($this->uptasksCount).' upcoming tasks in the next week.')
                    ->action('Please click here to check it out',$loginUrl)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
