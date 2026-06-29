<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteAccountVerification extends Notification
{
    use Queueable;

    public function __construct(public string $signedUrl)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirm Account Deletion')
            ->line('You requested to delete your account.')
            ->line('Please note that all your personal data will be permanently removed from our system after your account is deleted.')
            ->line('Click the button below to confirm. This link will expire in 60 minutes.')
            ->action('Delete Account', $this->signedUrl)
            ->line('If you did not request this, you can ignore this email.');
    }
}
