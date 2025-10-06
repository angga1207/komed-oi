<?php

namespace App\Livewire\Admin\MediaKontrak;

use Livewire\Component;
use App\Models\MediaPers;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class Chart extends Component
{
    use LivewireAlert;
    public $pers;

    function mount($unique_id)
    {
        $pers = MediaPers::where('unique_id', $unique_id)->firstOrFail();
        $this->pers = $pers;
    }

    public function render()
    {
        // CHART START
        $chartRiwayat = LivewireCharts::multiColumnChartModel()
            ->setTitle('Riwayat Kontrak')
            ->setAnimated(true)
            ->setLegendVisibility(false)
            ->setDataLabelsEnabled(true)
            ->setSmoothCurve()
            ->withDataLabels()
            ->setXAxisVisible(true)
            ->setYAxisVisible(true)
            ->withGrid();

        $range = range(date('Y') + 1, 2024);
        $range = array_reverse($range);
        foreach ($range as $year) {
            $kontrak = $this->pers->KontrakInduk($year)->first() ? $this->pers->KontrakInduk($year)->first()->nilai_kontrak : 0;

            $chartRiwayat->addSeriesColumn('Kontrak Induk', $year, floatval($kontrak));

            $kontrakAPBDP = $this->pers->KontrakAPBDP($year)->first() ? $this->pers->KontrakAPBDP($year)->first()->nilai_kontrak : 0;
            $chartRiwayat->addSeriesColumn('Kontrak APBDP', $year, floatval($kontrakAPBDP));
        }
        // dd($chartRiwayat);
        // CHART END

        return view('livewire.admin.media-kontrak.chart', [
            'chartRiwayat' => $chartRiwayat,
        ]);
    }
}
