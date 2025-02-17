<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Kreait\Laravel\Firebase\Facades\Firebase;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class Navbar extends Component
{
    public function render()
    {
        $notifications = auth()->user()->notifications->take(5);
        return view('livewire.admin.components.navbar', [
            'notifications' => $notifications
        ]);
    }

    function logout()
    {
        $user = auth()->user();
        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => $user->id,
            'action' => 'logout',
            'model' => 'users',
            'endpoint' => 'logout',
            'payload' => json_encode(request()->all()),
            'message' => 'Keluar dari aplikasi website'
        ];
        DB::table('user_logs')->insert($log);
        // make log end

        auth()->logout();
        return redirect()->route('login');
    }

    function markAsReadNotif($id)
    {
        DB::beginTransaction();
        try {
            DB::table('notifications')
                ->where('id', $id)
                ->update([
                    'read_at' => now(),
                ]);
            DB::commit();
            $notif = DB::table('notifications')
                ->where('id', $id)
                ->first();
            if ($notif->type == 'App\Notifications\OrderNotifications') {
                return redirect()->route('media-order');
            };
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
