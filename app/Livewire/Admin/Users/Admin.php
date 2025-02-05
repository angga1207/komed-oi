<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Admin extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $detail = [], $inputType = 'create';
    public $randomPassword, $resetId, $bannedId, $activeId;

    function getListeners()
    {
        return [
            'resetPassword' => 'resetPassword',
            'banned' => 'banned',
            'activated' => 'activated',
        ];
    }

    function mount()
    {
        $this->inputType = 'create';
    }

    public function render()
    {
        $datas = User::search($this->search)
            ->whereIn('role_id', [2, 3])
            ->paginate(10);
        $arrRoles = DB::table('roles')
            ->whereIn('id', [2, 3])
            ->get();

        return view('livewire.admin.users.admin', [
            'datas' => $datas,
            'arrRoles' => $arrRoles,
        ])
            ->layout('layouts.app', ['title' => 'Daftar Akun Admin']);
    }

    function addData()
    {
        $this->resetErrorBag();
        $this->inputType = 'create';
        $this->detail = [
            'id' => null,
            'name' => null,
            'email' => null,
            'role_id' => null,
            'status' => null,
            'password' => null,
            'password_confirmation' => null,
        ];
    }

    function saveData()
    {
        if ($this->inputType == 'create') {
            $this->validate([
                'detail.fullname' => 'required',
                'detail.username' => 'required|string|max:12|min:5|unique:users,username',
                'detail.email' => 'required|email|unique:users,email',
                'detail.role_id' => 'required',
                'detail.password' => 'required|min:8|string',
                'detail.password_confirmation' => 'required|same:detail.password',
            ], [], [
                'detail.fullname' => 'Nama Lengkap',
                'detail.username' => 'Username',
                'detail.email' => 'Email',
                'detail.role_id' => 'Role',
                'detail.password' => 'Password',
                'detail.password_confirmation' => 'Konfirmasi Password',
            ]);

            $this->detail['password'] = bcrypt($this->detail['password']);
            $firstName = explode(' ', $this->detail['fullname']);
            $lastName = explode(' ', $this->detail['fullname']);
            DB::table('users')->insert([
                'fullname' => $this->detail['fullname'],
                'first_name' => $firstName[0],
                'last_name' => $lastName[1] ?? '',
                'username' => $this->detail['username'],
                'email' => $this->detail['email'],
                'photo' => 'storage/images/users/default.png',
                'role_id' => $this->detail['role_id'],
                'status' => 'active',
                'password' => $this->detail['password'],
            ]);

            $this->alert('success', 'Data berhasil disimpan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil disimpan',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'create',
                'model' => 'users',
                'endpoint' => 'user/admin',
                'payload' => json_encode(request()->all()),
                'message' => 'Menambahkan akun admin baru dengan username ' . $this->detail['username']
            ];
            DB::table('user_logs')->insert($log);
            // make log end

        } else if ($this->inputType == 'update') {
            $this->validate([
                'detail.fullname' => 'required',
                'detail.username' => 'required|string|max:12|min:5|unique:users,username,' . $this->detail['id'],
                'detail.email' => 'required|email|unique:users,email,' . $this->detail['id'],
                'detail.role_id' => 'required',
                'detail.password' => 'nullable|min:8|string',
                'detail.password_confirmation' => 'nullable|same:detail.password',
            ], [], [
                'detail.fullname' => 'Nama Lengkap',
                'detail.username' => 'Username',
                'detail.email' => 'Email',
                'detail.role_id' => 'Role',
                'detail.password' => 'Password',
                'detail.password_confirmation' => 'Konfirmasi Password',
            ]);

            DB::table('users')
                ->where('id', $this->detail['id'])
                ->update([
                    'fullname' => $this->detail['fullname'],
                    'username' => $this->detail['username'],
                    'email' => $this->detail['email'],
                    'role_id' => $this->detail['role_id'],
                ]);

            $this->alert('success', 'Data berhasil diperbarui', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil diperbarui',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'update',
                'model' => 'users',
                'endpoint' => 'user/admin',
                'payload' => json_encode(request()->all()),
                'message' => 'Memperbarui data akun admin dengan username ' . $this->detail['username']
            ];
            DB::table('user_logs')->insert($log);
            // make log end
        }
        $this->resetErrorBag();
    }

    function getDetail($id)
    {
        $this->resetErrorBag();
        $this->detail = User::where('id', $id)
            ->first()
            ->toArray();
        $this->inputType = 'update';
    }

    function closeModal()
    {
        $this->detail = null;
        $this->inputType = 'create';
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
        $user = User::find($this->resetId);
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
            'endpoint' => 'user/admin',
            'payload' => json_encode(request()->all()),
            'message' => 'Mereset Password akun Admin dengan username ' . $user->username
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
        $user = User::find($this->bannedId);

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
            'endpoint' => 'user/admin',
            'payload' => json_encode(request()->all()),
            'message' => 'Memblokir akun Admin dengan username ' .  $user->username
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
        $user = User::find($this->activeId);

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
            'endpoint' => 'user/admin',
            'payload' => json_encode(request()->all()),
            'message' => 'Mengaktifkan akun Admin dengan username ' . $user->username
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }
}
