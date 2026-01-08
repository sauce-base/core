<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $changedAt = now()->format('F j, Y, g:i a');

        return (new MailMessage)
            ->subject('Password Changed Successfully')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your password was successfully changed.')
            ->line('Change time: '.$changedAt)
            ->line('If you did not make this change, please contact us immediately.')
            ->action('View Profile', route('settings.profile'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'changed_at' => now()->toIso8601String(),
        ];
    }
}
