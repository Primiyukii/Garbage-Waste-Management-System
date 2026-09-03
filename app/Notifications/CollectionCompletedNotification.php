<?php

namespace App\Notifications;

use App\Models\CollectionRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectionCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public CollectionRecord $record)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'collection_completed',
            'record_id' => $this->record->id,
            'weight_kg' => $this->record->weight_kg,
            'message' => "Your collection was completed — {$this->record->weight_kg}kg collected",
        ];
    }
}
