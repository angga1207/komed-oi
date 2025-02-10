<?php

namespace App\Livewire\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Register extends Component
{
    use LivewireAlert;
    public $fullname, $username, $email, $whatsapp, $password, $password_confirmation;

    public function render()
    {
        return view('livewire.client.register')
            ->layout('layouts.auth', [
                'title' => 'Pendaftaran Akun'
            ]);
    }

    function register()
    {
        $this->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|numeric|digits:16|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'whatsapp' => 'required|string|numeric|digits_between:11,13|unique:users,whatsapp',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ], [], [
            'fullname' => 'Nama Lengkap',
            'username' => 'N.I.K',
            'email' => 'Email',
            'whatsapp' => 'Nomor Whatsapp',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password'
        ]);


        DB::beginTransaction();
        try {
            $now = now();

            $firstName = explode(' ', $this->fullname)[0];
            $lastName = explode(' ', $this->fullname)[1] ?? '';


            $userCheck = DB::table('users')->where('username', $this->username)->first();
            if (!$userCheck) {
                $userID = DB::table('users')->insertGetId([
                    'fullname' => $this->fullname,
                    'first_name' => $firstName ?? $this->fullname,
                    'last_name' => $lastName ?? '',
                    'username' => $this->username,
                    'email' => $this->email,
                    'whatsapp' => $this->whatsapp,
                    'password' => bcrypt($this->password),
                    'photo' => 'storage/images/users/default.png',
                    'role_id' => 4,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $user = User::find($userID);

                $unique_id = $this->generateUniqueID();

                $pers = DB::table('pers_profile')->insert([
                    'user_id' => $user->id,
                    'unique_id' => $unique_id,
                    'nik' => $this->username,
                    'whatsapp' => $this->whatsapp,
                    'verified_status' => null,
                    'verification_deadline' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => $user->id,
                    'action' => 'register',
                    'model' => 'akun',
                    'endpoint' => 'register',
                    'payload' => json_encode($this->all()),
                    'message' => 'Melakukan registrasi dari Website',
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end
                DB::commit();


                Auth::loginUsingId($user->id);

                $this->flash('success', 'Berhasil Mendaftar Akun', [
                    'position' =>  'center',
                    'timer' =>  null,
                    'toast' =>  false,
                    'text' =>  'Selamat datang, ' . $user->fullname,
                    'showCancelButton' =>  false,
                    'showConfirmButton' =>  true,
                    'confirmButtonText' =>  'Tutup',
                ], 'client-dashboard');
            } else {
                return $this->validationErrorResponse(['nik' => 'N.I.K sudah terdaftar']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function generateUniqueID()
    {
        $unique_id = 'PERS.' . date('my') . '.' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        if ($this->checkUniqueIDExists($unique_id) == false) {
            $this->generateUniqueID();
        }
        return $unique_id;
    }

    function checkUniqueIDExists($unique_id)
    {
        $pers = DB::table('pers_profile')->where('unique_id', $unique_id)->first();
        if ($pers) {
            return false;
        } else {
            return true;
        }
    }
}
