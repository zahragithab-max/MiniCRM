<?php

namespace App\Modules\Deals\Notifications;

use App\Modules\Deals\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DealStageChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Deal $deal,
        public int $oldStageId,
        public int $newStageId
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'deal_id' => $this->deal->id,
            'deal_title' => $this->deal->title,
            'old_stage_id' => $this->oldStageId,
            'new_stage_id' => $this->newStageId,
            'message' => 'Deal stage changed.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Deal Stage Changed')
            ->greeting('Hello ' . $notifiable->name)
            ->line('The stage of a deal has been changed.')
            ->line('Deal: ' . $this->deal->title)
            ->line('Old Stage ID: ' . $this->oldStageId)
            ->line('New Stage ID: ' . $this->newStageId);
    }
}
