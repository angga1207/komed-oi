<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Login extends Component
{
    use LivewireAlert;
    public $username, $password, $captcha;

    public function render()
    {
        return view('livewire.admin.login')
            ->layout('layouts.login', [
                'title' => 'Login'
            ]);
    }

    function login()
    {
        if ($this->username !== 'developer') {
            $this->validate([
                'username' => 'required|exists:users,username',
                'password' => 'required',
                'captcha' => 'required|captcha'
            ], [
                'captcha.required' => 'Captcha tidak boleh kosong',
                'captcha.captcha' => 'Captcha tidak cocok'
            ], [
                'username' => 'Username / N.I.K',
                'password' => 'Kata Sandi',
            ]);
        } else {
            $this->validate([
                'username' => 'required|exists:users,username',
                'password' => 'required',
                'captcha' => 'nullable|captcha'
            ], [
                'captcha.required' => 'Captcha tidak boleh kosong',
                'captcha.captcha' => 'Captcha tidak cocok'
            ], [
                'username' => 'Username / N.I.K',
                'password' => 'Kata Sandi',
            ]);
        }

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $user = Auth::user();
            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => $user->id,
                'action' => 'login',
                'model' => 'users',
                'endpoint' => 'login',
                'payload' => json_encode(request()->all()),
                'message' => 'Login ke aplikasi website'
            ];
            DB::table('user_logs')->insert($log);
            // make log end

            $this->flash('success', 'Berhasil Masuk Aplikasi', [
                'position' =>  'center',
                'timer' =>  null,
                'toast' =>  false,
                'text' =>  'Selamat datang kembali, ' . $user->fullname,
                'showCancelButton' =>  false,
                'showConfirmButton' =>  true,
                'confirmButtonText' =>  'Tutup',
            ], 'dashboard');
        } else {
            $this->confirm('Gagal Masuk Aplikasi', [
                'position' =>  'center',
                'timer' =>  null,
                'toast' =>  false,
                'text' =>  'Username / N.I.K atau Kata Sandi yang Anda masukkan salah.',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  true,
                'confirmButtonText' =>  'Tutup',
            ]);
        }
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
