<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkflowNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $message,
        private ?string $subject = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject ?? 'CRM Notification')
            ->greeting('Hello ' . $notifiable->name)
            ->line($this->message);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
        ];
    }
}