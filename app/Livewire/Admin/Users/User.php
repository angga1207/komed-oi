<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class User extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $detail = [];
    public $randomPassword, $resetId, $bannedId, $activeId;

    function getListeners()
    {
        return [
            'resetPassword' => 'resetPassword',
            'banned' => 'banned',
            'activated' => 'activated',
        ];
    }


    public function render()
    {
        $datas = ModelsUser::search($this->search)
            ->where('role_id', 4)
            ->paginate(10);
        return view('livewire.admin.users.user', [
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Daftar Akun Klien']);
    }

    function getDetail($id)
    {
        $this->detail = ModelsUser::where('id', $id)
            ->with('getMedia')
            ->first()
            ->toArray();
    }

    function closeModal()
    {
        $this->detail = null;
    }

    function confirmResetPassword($id)
    {
        $this->confirm('Apakah Anda yakin ingin mereset password akun ini?', [
            'text' => 'Password akun ini berhasil direset',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Reset Password',
            'onConfirmed' => 'resetPassword'
        ]);
        $this->resetId = $id;
    }

    function resetPassword()
    {
        $user = ModelsUser::find($this->resetId);
        $randomPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        if ($user) {
            $user->password = bcrypt($randomPassword);
            $user->save();

            $this->alert('success', 'Password akun ini berhasil direset', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Password akun ini berhasil direset, sebagai password baru gunakan: "' . $randomPassword . '"',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }
        $this->resetId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'reset_password',
            'model' => 'users',
            'endpoint' => 'user/user',
            'payload' => json_encode(request()->all()),
            'message' => 'Mereset Password akun User dengan username ' .  $user->username
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }

    function confirmBanned($id)
    {
        $this->confirm('Apakah Anda yakin ingin memblokir akun ini?', [
            'text' => 'Akun ini berhasil diblokir',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Blokir',
            'onConfirmed' => 'banned'
        ]);
        $this->bannedId = $id;
    }

    function banned()
    {
        $user = ModelsUser::find($this->bannedId);

        if ($user) {
            $user->status = 'banned';
            $user->save();

            $this->alert('success', 'Akun ini berhasil diblokir', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Akun ini berhasil diblokir',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->bannedId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'banned',
            'model' => 'users',
            'endpoint' => 'user/user',
            'payload' => json_encode(request()->all()),
            'message' => 'Memblokir akun User dengan username ' . $user->username
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }

    function confirmActivated($id)
    {
        $this->confirm('Apakah Anda yakin ingin mengaktifkan kembali akun ini?', [
            'text' => 'Akun ini berhasil diaktifkan',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Aktifkan',
            'onConfirmed' => 'activated'
        ]);
        $this->activeId = $id;
    }

    function activated()
    {
        $user = ModelsUser::find($this->activeId);

        if ($user) {
            $user->status = 'active';
            $user->save();

            $this->alert('success', 'Akun ini berhasil diaktifkan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Akun ini berhasil diaktifkan',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->activeId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'banned',
            'model' => 'users',
            'endpoint' => 'user/user',
            'payload' => json_encode(request()->all()),
            'message' => 'Mengaktifkan akun User dengan username ' . $user->username
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }
}
