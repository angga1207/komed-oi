<?php

namespace App\Livewire\Client\Media;

use Carbon\Carbon;
use App\Models\Order;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $search, $filterDate = null;
    public $filterStatus = null;

    public function render()
    {
        $datas = Order::search($this->search)
            ->where('media_id', auth()->user()->getMedia->id)
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterDate, function ($q) {
                $q->whereDate('tanggal_pelaksanaan', $this->filterDate);
            })
            ->latest('tanggal_pelaksanaan')
            ->latest('waktu_pelaksanaan')
            ->paginate(10);

        return view('livewire.client.media.index', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => "Daftar Media Order"
            ]);
    }

    function goSearch()
    {
        $this->resetPage();
    }

    function resetSearch()
    {
        $this->search = null;
        $this->filterStatus = null;
        $this->filterDate = null;
        $this->resetPage();
    }
}
