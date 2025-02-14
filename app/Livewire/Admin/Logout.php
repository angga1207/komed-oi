<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Logout extends Component
{
    function mount()
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

    public function render()
    {
        return view('livewire.admin.logout');
    }
}
