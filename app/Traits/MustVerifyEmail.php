<?php
namespace App\Traits;
use App\Notifications\ForgotPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }
/**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
/**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // echo $this->name;
        // Notification::send($this, new VerifyEmail);
        $this->notify(new VerifyEmail);
    }
    // public function sendPasswordResetNotification()
    // {
    //     $this->notify(new ForgotPassword);
    // }
    public function sendWelcomeEmailNotification()
    {
        $this->notify(new WelcomeEmail);
    }
    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
}