<?php

namespace App\Livewire\Admin\Report;

use App\Models\MediaPers;
use App\Models\Order;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $countMedia = MediaPers::where('verified_status', 'verified')->count();
        $countNilaiKerjaSama = 0;
        $countMediaOrder = Order::whereYear('tanggal_pelaksanaan', date('Y'))->count();
        $countRealisasiAnggaran = 0;
        $countDataBpk = 0;

        return view('livewire.admin.report.index', [
            'countMedia' => $countMedia,
            'countNilaiKerjaSama' => $countNilaiKerjaSama,
            'countMediaOrder' => $countMediaOrder,
            'countRealisasiAnggaran' => $countRealisasiAnggaran,
            'countDataBpk' => $countDataBpk,
        ])
            ->layout('layouts.app', ['title' => 'Laporan']);
    }
}
