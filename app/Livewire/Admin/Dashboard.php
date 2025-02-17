<?php

namespace App\Livewire\Admin;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Dashboard extends Component
{
    public function render()
    {
        $mediaPers = DB::table('pers_profile')
            ->where('verified_status', 'verified')
            ->get();
        $mediaOrders = DB::table('orders')
            ->whereMonth('tanggal_pelaksanaan', Carbon::now())
            ->get();


        $timelines = [];
        $arrAgendas = DB::table('agendas')
        // ->whereDate('tanggal_pelaksanaan', Carbon::now()->subDay())
            ->whereDate('tanggal_pelaksanaan', Carbon::now())
            ->orderBy('waktu_pelaksanaan')
            ->get();
        foreach ($arrAgendas as $agd) {
            $orders = DB::table('orders')->where('agenda_id', $agd->id)->get();
            $timelines[] = [
                'nama_acara' => $agd->nama_acara ?? null,
                'tanggal_pelaksanaan' => $agd->tanggal_pelaksanaan,
                'tanggal_pelaksanaan_akhir' => $agd->tanggal_pelaksanaan_akhir,
                'waktu_pelaksanaan' => $agd->waktu_pelaksanaan,
                'leading_sector' => $agd->leading_sector,
                'lokasi' => $agd->lokasi,
                'orders' => $orders ?? [],
            ];
        }

        // CHART START
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
                // ->where('media_id', $this->pers->id)
                ->whereDate('tanggal_pelaksanaan', $dateYMD)
                ->get();
            $chartMediaOrder->addSeriesPoint('Media Order', $dateStr, count($order));
            $chartMediaOrder->addSeriesPoint('Selesai', $dateStr, count($order->whereIn('status', ['verified', 'done'])));
            $chartMediaOrder->addSeriesPoint('Dikerjakan', $dateStr, count($order->whereIn('status', ['sent'])));
            $chartMediaOrder->addSeriesPoint('Direview', $dateStr, count($order->whereIn('status', ['review'])));
        }
        // CHART END

        return view('livewire.admin.dashboard', [
            'mediaPers' => $mediaPers,
            'mediaOrders' => $mediaOrders,
            'timelines' => $timelines,
            'chartMediaOrder' => $chartMediaOrder,
        ])
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
