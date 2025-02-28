<?php

namespace App\Livewire\Client\Media;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Report extends Component
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
            ->whereNotIn('status', ['unsent', 'sent'])
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->where(function ($q) {
                if ($this->filterDate) {
                    return $q->whereDate('tanggal_pelaksanaan', $this->filterDate);
                } else {
                    $q->whereDate('tanggal_pelaksanaan', date('Y-m-d'));
                }
            })
            ->latest('tanggal_pelaksanaan')
            ->latest('waktu_pelaksanaan')
            ->paginate(10);

        if ($this->getPage() == 1 && !$this->search && !$this->filterDate && !$this->filterStatus && $datas->count() == 0) {
            $latestDate = DB::table('orders')
                ->whereNotIn('status', ['unsent', 'sent'])
                ->where('media_id', auth()->user()->getMedia->id)
                ->max('tanggal_pelaksanaan');
            $this->filterDate = Carbon::parse($latestDate)->isoFormat('Y-MM-DD');
            $datas = Order::where('media_id', auth()->user()->getMedia->id)
                ->whereNotIn('status', ['unsent', 'sent'])
                ->whereDate('tanggal_pelaksanaan', $latestDate)
                ->latest('tanggal_pelaksanaan')
                ->latest('waktu_pelaksanaan')
                ->paginate(10);
        }

        return view('livewire.client.media.report', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => "Laporan"
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
