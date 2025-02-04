<?php

namespace App\Livewire\Admin;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Login extends Component
{
    use LivewireAlert;
    public $username, $password;

    public function render()
    {
        return view('livewire.admin.login')
            ->layout('layouts.auth');
    }

    function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $this->alert('success', 'Login Success', [
            'position' =>  'top-end',
            'timer' =>  3000,
            'toast' =>  true,
            'text' =>  'Welcome back, ' . $this->username,
            'showCancelButton' =>  false,
            'showConfirmButton' =>  false,
        ]);
    }
}
