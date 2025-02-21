<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class RegVerifNotification extends Notification
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
        $title = 'Registration Verified';
        $body = 'Permintaan verifikasi media pers telah diverifikasi. Silahkan cek media pers anda.';

        $notif = FcmNotification::create()
            // ->title('Registration Verified')
            // ->body('Permintaan verifikasi media pers telah diverifikasi. Silahkan cek media pers anda.')
            ->image(null);
        $admin = User::find($this->fromUserId);

        $data = FcmMessage::create()
            ->name('Registration Verified')
            ->token($this->token)
            ->topic('reg_verified')
            ->condition('condition')
            ->notification($notif)
            ->data([
                'title' => $title,
                'body' => $body,
            ]);
        return $data;
    }

    public function toArray(object $notifiable): array
    {
        $admin = User::find($this->fromUserId);
        $returns = [
            'title' => 'Registration Verified',
            'message' => 'Permintaan verifikasi media pers telah diverifikasi. Silahkan cek media pers anda.',
            'admin_id' => $admin->id,
            'admin_name' => $admin->fullname,
            'verified_at' => now(),
            'media' => $this->media,
        ];
        return $returns;
    }
}
