<?php

namespace App\Livewire\Admin\Report;

use App\Models\MediaPers;
use Livewire\Attributes\Url;
use Livewire\Component;

class AnggaranPerMedia extends Component
{
    #[Url('filterJenis', null, true)]
    public $filterJenis = 'all';
    public $filterTahun;

    public function goTo($jenis)
    {
        redirect()->to(route('reports.anggaran-per-media', [
            'filterJenis' => $jenis,
            'filterTahun' => $this->filterTahun,
        ]));
    }

    public function render()
    {
        $allMedias = MediaPers::all();
        $datas = MediaPers::query();
        $datas->where('verified_status', 'verified');

        if ($this->filterJenis != 'all') {
            $datas = $datas->where('jenis_media', $this->filterJenis);
        }
        if ($this->filterTahun) {
            $datas = $datas->whereYear('created_at', $this->filterTahun);
        }
        $datas = $datas->get();

        return view('livewire.admin.report.anggaran-per-media', [
            'allMedias' => $allMedias,
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Realisasi Anggaran Per Media']);
    }
}
