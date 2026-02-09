<?php

namespace App\Livewire\Client;

use App\Models\MediaPers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Register extends Component
{
    use LivewireAlert;
    public $fullname, $username, $email, $whatsapp, $password, $password_confirmation;
    public $captcha;

    public function render()
    {
        return view('livewire.client.register')
            ->layout('layouts.login', [
                'title' => 'Pendaftaran Akun'
            ]);
    }

    function register()
    {
        // Registration is Closed
        $this->flash('info', 'Pendaftaran Ditutup', [
            'position' =>  'center',
            'timer' =>  null,
            'toast' =>  false,
            'text' =>  'Maaf, pendaftaran akun baru sudah ditutup.',
            'showCancelButton' =>  false,
            'showConfirmButton' =>  true,
            'confirmButtonText' =>  'Tutup',
        ], 'dashboard');
        return;

        $this->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|numeric|digits:16|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'whatsapp' => 'required|string|numeric|digits_between:11,13|unique:users,whatsapp',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'captcha' => 'required|captcha'
        ],  [
            'captcha.required' => 'Captcha tidak boleh kosong',
            'captcha.captcha' => 'Captcha tidak cocok'
        ], [
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

                $pers = new MediaPers();
                $pers->user_id = $user->id;
                // $pers->unique_id = $unique_id;
                $pers->nik = $this->username;
                $pers->whatsapp = $this->whatsapp;
                $pers->verified_status = null;
                $pers->verification_deadline = null;
                $pers->save();

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
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
