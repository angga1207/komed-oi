<?php

namespace App\Livewire\Admin\Report;

use App\Models\MediaPers;
use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;

class MediaOrderDetail extends Component
{
    #[Url('filterTahun', null, true)]
    public $filterTahun;
    public $years = [];
    public $mediaId, $media;

    public function mount($mediaId)
    {
        $this->mediaId = $mediaId;
        $this->media = MediaPers::findOrFail($this->mediaId);
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
            redirect()->to(route('reports.media-order.detail', [
                'mediaId' => $this->mediaId,
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
        $datas = Order::query()->where('media_id', $this->mediaId);
        if ($this->filterTahun) {
            $datas = $datas->whereYear('tanggal_pelaksanaan', $this->filterTahun);
        }
        $datas = $datas->orderBy('tanggal_pelaksanaan', 'desc')->get();

        return view('livewire.admin.report.media-order-detail', [
            'datas' => $datas
        ])
            ->layout('layouts.app', ['title' => 'Rekap Media Order ' . $this->media->nama_media . ' Tahun ' . $this->filterTahun]);
    }
}
