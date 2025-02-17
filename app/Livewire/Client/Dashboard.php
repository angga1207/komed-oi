<?php

namespace App\Livewire\Client;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Dashboard extends Component
{
    use LivewireAlert;
    public $isMediaUnverified = false;
    public $pers;

    function mount()
    {
        $pers = DB::table('pers_profile')->where('user_id', auth()->id())->first();
        if ($pers->verified_status == 'verified') {
            $this->isMediaUnverified = true;
        } else {
            $this->isMediaUnverified = false;
        }
        $this->pers = $pers;
    }

    public function render()
    {
        $mediaOrders = DB::table('orders')
            ->where('media_id', $this->pers->id)
            ->select(['id', 'order_code', 'status'])
            ->get();

        $timelines = [];
        $arrTimeline = DB::table('orders')
            ->where('media_id', $this->pers->id)
            // ->whereDate('tanggal_pelaksanaan', Carbon::now()->subDay())
            ->whereDate('tanggal_pelaksanaan', Carbon::now())
            ->orderBy('waktu_pelaksanaan')
            ->get();
        foreach ($arrTimeline as $tl) {
            $agenda = DB::table('agendas')->where('id', $tl->agenda_id)->first();
            $dataAgenda = collect(json_decode($agenda->data));

            $timelines[] = [
                'id' => $tl->id,
                'order_code' => $tl->order_code,
                'nama_acara' => $dataAgenda['nama_acara'] ?? null,
                'tanggal_pelaksanaan' => $tl->tanggal_pelaksanaan,
                'tanggal_pelaksanaan_akhir' => $tl->tanggal_pelaksanaan_akhir,
                'waktu_pelaksanaan' => $tl->waktu_pelaksanaan,
                'leading_sector' => $tl->leading_sector,
                'lokasi' => $dataAgenda['tempat_pelaksanaan_array'][0] ?? null,
                'status' => $tl->status,
                'created_at' => $tl->created_at,
                'deadline' => Carbon::parse($tl->created_at)->addDays(7)->isoFormat('Y-MM-DD HH:mm:ss'),
            ];
        }

        $chartMediaOrder = LivewireCharts::multiLineChartModel()
            ->setTitle('Media Order Bulan Ini')
            ->setAnimated(true)
            ->setLegendVisibility(false)
            ->setDataLabelsEnabled(true)
            ->setSmoothCurve()
            ->withDataLabels()
            ->setXAxisVisible(true);

        $range = CarbonPeriod::create(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        foreach ($range as $date) {
            $dateYMD = Carbon::parse($date)->isoFormat('Y-MM-DD');
            $dateStr = Carbon::parse($date)->isoFormat('D MMM YY');
            $order = DB::table('orders')
                ->where('media_id', $this->pers->id)
                ->whereDate('tanggal_pelaksanaan', $dateYMD)
                ->get();
            $chartMediaOrder->addSeriesPoint('Media Order', $dateStr, count($order));
            $chartMediaOrder->addSeriesPoint('Selesai', $dateStr, count($order->whereIn('status', ['verified', 'done'])));
            $chartMediaOrder->addSeriesPoint('Dikerjakan', $dateStr, count($order->whereIn('status', ['sent'])));
            $chartMediaOrder->addSeriesPoint('Direview', $dateStr, count($order->whereIn('status', ['review'])));
        }

        return view('livewire.client.dashboard', [
            'mediaOrders' => $mediaOrders,
            'timelines' => $timelines,
            'chartMediaOrder' => $chartMediaOrder,
        ])
            ->layout('layouts.app', [
                'title' => "Dashboard"
            ]);
    }
}
