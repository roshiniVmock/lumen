<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
class ForgotPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public static $toMailCallback;
    public function __construct()
    {
        //
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
        $passwordresetUrl = $this->passwordresetUrl($notifiable);
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $passwordresetUrl);
        }
        return (new MailMessage)
                    ->subject(Lang::get('Reset Password'))
                    ->line(Lang::get('Please click the button below to reset your password.'))
                    ->action(Lang::get('Reset Password'), $passwordresetUrl)
                    ->line(Lang::get('If you did not request a pasword reset for your account, no further action is required.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function passwordresetUrl($notifiable)
    {
        $token = JWTAuth::fromUser($notifiable);
        return route('email.reset-password', ['token' => $token], false);
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
