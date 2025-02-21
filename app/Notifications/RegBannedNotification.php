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

class RegBannedNotification extends Notification
{
    use Queueable;
    public $media, $token, $fromUserId;

    public function __construct($media, $token, $fromUserId)
    {
        $this->media = $media;
        $this->token = $token;
        $this->fromUserId = $fromUserId;
    }

    public function via(object $notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    public function toFcm($notifiable): FcmMessage
    {
        $title = 'Registration Rejected';
        $body = 'Permintaan verifikasi media pers telah ditolak. Silahkan hubungi admin.';

        $notif = FcmNotification::create()
            // ->title('Registration Rejected')
            // ->body('Permintaan verifikasi media pers telah ditolak. Silahkan hubungi admin.')
            ->image(null);
        $admin = User::find($this->fromUserId);

        $data = FcmMessage::create()
            ->name('Registration Rejected')
            ->token($this->token)
            ->topic('reg_rejected')
            ->condition('condition')
            ->data([
                'title' => $title,
                'message' => $body,
            ])
            ->notification($notif);
        return $data;
    }

    public function toArray(object $notifiable): array
    {
        $admin = User::find($this->fromUserId);
        $returns = [
            'title' => 'Registration Rejected',
            'message' => 'Permintaan verifikasi media pers telah ditolak. Silahkan hubungi admin.',
            'admin_id' => $admin->id,
            'admin_name' => $admin->fullname,
            'verified_at' => now(),
            'media' => $this->media,
        ];
        return $returns;
    }
}
