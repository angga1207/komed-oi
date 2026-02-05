<?php

namespace App\Livewire\Admin\Report;

use App\Models\MediaPers;
use Livewire\Attributes\Url;
use Livewire\Component;

class MediaOrder extends Component
{
    #[Url('filterJenis', null, true)]
    public $filterJenis = 'all';
    #[Url('filterTahun', null, true)]
    public $filterTahun;
    public $years = [];

    public function mount()
    {
        $this->filterTahun = !$this->filterTahun ? date('Y') : $this->filterTahun;
        $startYear = 2024;
        $currentYear = date('Y');
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $this->years[] = $year;
        }
    }

    public function updated($field)
    {
        if ($field == 'filterTahun') {
            redirect()->to(route('reports.media-order', [
                'filterJenis' => $this->filterJenis,
                'filterTahun' => $this->filterTahun,
            ]));
        }
    }

    public function goTo($jenis)
    {
        redirect()->to(route('reports.media-order', [
            'filterJenis' => $jenis,
        ]));
    }

    public function render()
    {
        $allMedias = MediaPers::all();
        $datas = MediaPers::query()->with(['Orders']);
        $datas->where('verified_status', 'verified');

        if ($this->filterJenis != 'all') {
            $datas = $datas->where('jenis_media', $this->filterJenis);
        }
        if ($this->filterTahun) {
            $datas = $datas->whereHas('Orders', function ($query) {
                $query->whereYear('tanggal_pelaksanaan', $this->filterTahun);
            });

            $datas = $datas->with(['Orders' => function ($query) {
                $query->whereYear('tanggal_pelaksanaan', $this->filterTahun);
            }]);
        }
        $datas = $datas->get();
        // $datas = $datas->first();
        // dd($datas);

        return view('livewire.admin.report.media-order', [
            'allMedias' => $allMedias,
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Jumlah Media Order Per Media']);
    }
}
