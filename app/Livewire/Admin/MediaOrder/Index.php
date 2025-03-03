<?php

namespace App\Livewire\Admin\MediaOrder;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
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
            // ->when($this->filterStatus, function ($q) {
            //     $q->where('status', $this->filterStatus);
            // })
            ->whereNotIn('status', ['unsent'])
            ->where(function ($q) {
                if ($this->filterDate) {
                    return $q->whereDate('tanggal_pelaksanaan', $this->filterDate);
                } else {
                    $q->whereDate('tanggal_pelaksanaan', date('Y-m-d'));
                }
            })
            ->latest('tanggal_pelaksanaan')
            ->latest('waktu_pelaksanaan')
            ->paginate(5);
        if ($this->getPage() == 1 && !$this->search && !$this->filterDate && !$this->filterStatus && $datas->count() == 0) {
            $latestDate = DB::table('orders')
                ->whereNotIn('status', ['unsent'])
                ->max('tanggal_pelaksanaan');
            $this->filterDate = Carbon::parse($latestDate)->isoFormat('Y-MM-DD');
            $datas = Order::whereDate('tanggal_pelaksanaan', $latestDate)
                ->whereNotIn('status', ['unsent'])
                ->latest('tanggal_pelaksanaan')
                ->latest('waktu_pelaksanaan')
                ->paginate(5);
        }

        return view('livewire.admin.media-order.index', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => "Laporan"
            ]);
    }

    function resetSearch()
    {
        $this->search = null;
        $this->filterDate = null;
        $this->filterStatus = null;
        $this->resetPage();
    }

    function goSearch()
    {
        $this->resetPage();
    }
}
