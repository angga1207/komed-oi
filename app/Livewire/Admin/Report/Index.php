<?php

namespace App\Livewire\Admin\Report;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.report.index')
            ->layout('layouts.app', ['title' => 'Laporan']);
    }
}
