<?php

namespace App\Livewire\Admin\Report;

use App\Models\Order;
use Livewire\Component;
use App\Models\MediaPers;
use Livewire\Attributes\Url;

class DataBpk extends Component
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
            redirect()->to(route('reports.data-bpk', [
                'filterJenis' => $this->filterJenis,
                'filterTahun' => $this->filterTahun,
            ]));
        }
    }

    public function goTo($jenis)
    {
        redirect()->to(route('reports.data-bpk', [
            'filterJenis' => $jenis,
        ]));
    }

    public function render()
    {
        $datas = Order::query();
        if ($this->filterJenis != 'all') {
            $datas = $datas->whereHas('MediaPers', function ($q) {
                $q->where('jenis_media', $this->filterJenis)->where('verified_status', 'verified');
            });
        }
        if ($this->filterTahun) {
            $datas = $datas->whereYear('tanggal_pelaksanaan', $this->filterTahun);
        }
        $datas = $datas->orderBy('tanggal_pelaksanaan', 'desc')->get();

        return view('livewire.admin.report.data-bpk', [
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Data Permintaan BPK']);
    }
}
