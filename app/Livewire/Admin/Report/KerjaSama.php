<?php

namespace App\Livewire\Admin\Report;

use App\Models\MediaPers;
use Livewire\Attributes\Url;
use Livewire\Component;

class KerjaSama extends Component
{
    #[Url('filterJenis', null, true)]
    public $filterJenis = 'all';
    public $filterTahun;

    public function goTo($jenis)
    {
        redirect()->to(route('reports.kerja-sama', [
            'filterJenis' => $jenis,
        ]));
    }

    public function render()
    {
        $allMedias = MediaPers::where('verified_status', 'verified')->get();
        $datas = MediaPers::query();
        $datas->where('verified_status', 'verified');

        if ($this->filterJenis != 'all') {
            $datas = $datas->where('jenis_media', $this->filterJenis);
        }
        if ($this->filterTahun) {
            $datas = $datas->whereYear('created_at', $this->filterTahun);
        }
        $datas = $datas->get();

        return view('livewire.admin.report.kerja-sama', [
            'allMedias' => $allMedias,
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Nilai Kerja Sama Per Media']);
    }
}
