<?php

namespace App\Livewire\Admin;

use App\Models\MediaPers;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Dashboard extends Component
{
    public $year;

    function mount()
    {
        $this->year = date('Y');
    }

    public function render()
    {
        $mediaPers = DB::table('pers_profile')
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

        // Kontrak Media
        $jenisMedia = MediaPers::select('jenis_media')->distinct()
            ->pluck('jenis_media')
            ->toArray();
        $kontrakMedia = [];
        foreach ($jenisMedia as $jm) {
            $medias = MediaPers::where('jenis_media', $jm)->get();
            $totalNilaiKontrakInduk = 0;
            $totalNilaiKontrakAPBDP = 0;
            foreach ($medias as $md) {
                $kontrak1 = $md->KontrakInduk($this->year)->first() ? $md->KontrakInduk($this->year)->first()->nilai_kontrak : 0;
                $totalNilaiKontrakInduk += $kontrak1;
                $kontrak2 = $md->KontrakAPBDP($this->year)->first() ? $md->KontrakAPBDP($this->year)->first()->nilai_kontrak : 0;
                $totalNilaiKontrakAPBDP += $kontrak2;
            }
            $kontrakMedia[$jm]['induk'] = $totalNilaiKontrakInduk;
            $kontrakMedia[$jm]['apbdp'] = $totalNilaiKontrakAPBDP;
        }
        $kontrakMedia['TOTAL']['induk'] = array_sum(array_column($kontrakMedia, 'induk'));
        $kontrakMedia['TOTAL']['apbdp'] = array_sum(array_column($kontrakMedia, 'apbdp'));

        return view('livewire.admin.dashboard', [
            'mediaPers' => $mediaPers,
            'mediaOrders' => $mediaOrders,
            'timelines' => $timelines,
            'chartMediaOrder' => $chartMediaOrder,
            'kontrakMedia' => $kontrakMedia,
        ])
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
