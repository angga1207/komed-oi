<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegVerifNotification extends Notification
{
    use Queueable;

    public $media, $toUserIds, $fromUserId;

    public function __construct($media, $toUserIds, $fromUserId)
    {
        $this->media = $media;
        $this->toUserIds = $toUserIds;
        $this->fromUserId = $fromUserId;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
