<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $userType;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $userType)
    {
        $this->token = $token;
        $this->userType = $userType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
            'userType' => $this->userType, // Adding userType to the reset link
        ], false));

        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', $resetUrl)
            ->line('Thank you for using our application!');
    }

    // public function sendPasswordResetNotification($token)
    // {
    //     $userType = $this->userType === 'creator' ? 'creator' : 'brand';
    //     $this->notify(new CustomResetPasswordNotification($token, $userType));
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
