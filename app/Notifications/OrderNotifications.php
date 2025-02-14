<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Illuminate\Support\Facades\DB;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class OrderNotifications extends Notification
{
    use Queueable;
    public $media, $token, $fromUserId, $order, $type;

    public function __construct($media, $order, $type, $token, $fromUserId)
    {
        $this->media = $media;
        $this->token = $token;
        $this->order = $order;
        $this->type = $type;
        $this->fromUserId = $fromUserId;
    }

    public function via(object $notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    public function toFcm($notifiable): FcmMessage
    {
        $title = null;
        $body = null;
        if ($this->type == 'sent') {
            $title = 'Media Order baru dikirim oleh Admin';
            $body = 'Admin KOMED ID baru saja mengirimkan Media Order baru';
        }

        if ($title && $body) {
            $notif = FcmNotification::create()
                ->title($title)
                ->body($body)
                ->image(null);
            $admin = User::find($this->fromUserId);

            $data = FcmMessage::create()
                ->name($title)
                ->token($this->token)
                ->topic('order')
                ->notification($notif);
            return $data;
        }
    }

    public function toArray(object $notifiable): array
    {
        $title = null;
        $body = null;
        if ($this->type == 'sent') {
            $title = 'Media Order baru dikirim oleh Admin';
            $body = 'Admin KOMED ID baru saja mengirimkan Media Order baru';
        }


        if ($title && $body) {
            $admin = User::find($this->fromUserId);
            $returns = [
                'title' => $title,
                'message' => $body,
                'admin_id' => $admin->id,
                'admin_name' => $admin->fullname,
                'type' => $this->type,
                'media' => $this->media,
                'order' => $this->order,
            ];
            return $returns;
        }
    }
}
