<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
