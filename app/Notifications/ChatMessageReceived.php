<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChatMessageReceived extends Notification
{
    use Queueable;

    public function __construct(
        public int $conversationId,
        public int $senderId,
        public string $senderName,
        public string $body,
        public bool $sendEmail = false
    ) {
    }

    public function via(object $notifiable): array
    {
        return $this->sendEmail ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New chat message from ' . $this->senderName)
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line($this->senderName . ' sent you a chat message.')
            ->line($this->body)
            ->action('Open Messages', route('user.messages.show', $this->conversationId));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'body' => $this->body,
            'url' => route('user.messages.show', $this->conversationId),
        ];
    }
}
