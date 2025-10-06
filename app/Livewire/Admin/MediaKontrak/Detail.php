<?php

namespace App\Livewire\Admin\MediaKontrak;

use Livewire\Component;
use App\Models\MediaPers;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Detail extends Component
{
    use LivewireAlert;
    public $pers;
    public $year, $kontrakInduk, $kontrakAPBDP;

    function mount($unique_id)
    {
        $pers = MediaPers::where('unique_id', $unique_id)->firstOrFail();
        $this->pers = $pers;
        $this->year = date('Y');

        $kontrakInduk = $this->pers->KontrakInduk($this->year)->first();
        $kontrakAPBDP = $this->pers->KontrakAPBDP($this->year)->first();
        $this->kontrakInduk = $kontrakInduk ? [
            'id' => $kontrakInduk->id,
            'tahun' => $this->year,
            'nilai_kontrak' => floatval($kontrakInduk->nilai_kontrak),
            'jenis_kontrak' => 'induk',
            'nomor_kontrak' => $kontrakInduk->nomor_kontrak,
        ] : [
            'id' => null,
            'tahun' => $this->year,
            'nilai_kontrak' => 0,
            'jenis_kontrak' => 'induk',
            'nomor_kontrak' => '',
        ];
        $this->kontrakAPBDP = $kontrakAPBDP ? [
            'id' => $kontrakAPBDP->id,
            'tahun' => $this->year,
            'nilai_kontrak' => floatval($kontrakAPBDP->nilai_kontrak),
            'jenis_kontrak' => 'apbdp',
            'nomor_kontrak' => $kontrakAPBDP->nomor_kontrak,
        ] : [
            'id' => null,
            'tahun' => $this->year,
            'nilai_kontrak' => 0,
            'jenis_kontrak' => 'apbdp',
            'nomor_kontrak' => '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.media-kontrak.detail')
            ->layout('layouts.app', [
                'title' => $this->pers->nama_media,
            ]);
    }

    function updated($field)
    {
        if ($field == 'year') {
            $kontrakInduk = $this->pers->KontrakInduk($this->year)->first();
            $kontrakAPBDP = $this->pers->KontrakAPBDP($this->year)->first();
            $this->kontrakInduk = $kontrakInduk ? [
                'id' => $kontrakInduk,
                'tahun' => $this->year,
                'nilai_kontrak' => floatval($kontrakInduk->nilai_kontrak),
                'jenis_kontrak' => 'induk',
                'nomor_kontrak' => $kontrakInduk->nomor_kontrak,
            ] : [
                'id' => null,
                'tahun' => $this->year,
                'nilai_kontrak' => 0,
                'jenis_kontrak' => 'induk',
                'nomor_kontrak' => '',
            ];
            $this->kontrakAPBDP = $kontrakAPBDP ? [
                'id' => $kontrakAPBDP,
                'tahun' => $this->year,
                'nilai_kontrak' => floatval($kontrakAPBDP->nilai_kontrak),
                'jenis_kontrak' => 'apbdp',
                'nomor_kontrak' => $kontrakAPBDP->nomor_kontrak,
            ] : [
                'id' => null,
                'tahun' => $this->year,
                'nilai_kontrak' => 0,
                'jenis_kontrak' => 'apbdp',
                'nomor_kontrak' => '',
            ];
        }
    }

    function saveKontrak()
    {
        $this->kontrakInduk['nilai_kontrak'] = str_replace(',00', '', $this->kontrakInduk['nilai_kontrak']);
        $this->kontrakInduk['nilai_kontrak'] = floatval(str_replace('.', '', $this->kontrakInduk['nilai_kontrak']));

        $this->kontrakAPBDP['nilai_kontrak'] = str_replace(',00', '', $this->kontrakAPBDP['nilai_kontrak']);
        $this->kontrakAPBDP['nilai_kontrak'] = floatval(str_replace('.', '', $this->kontrakAPBDP['nilai_kontrak']));
        $this->validate([
            'kontrakInduk.nilai_kontrak' => 'required|numeric|min:0',
            'kontrakAPBDP.nilai_kontrak' => 'required|numeric|min:0',
        ], [], [
            'kontrakInduk.nilai_kontrak' => 'Nilai Kontrak Induk',
            'kontrakAPBDP.nilai_kontrak' => 'Nilai Kontrak APBDP',
        ]);

        DB::beginTransaction();
        try {
            // Kontrak Induk
            if ($this->kontrakInduk['id']) {
                // Update
                $this->pers->KontrakInduk()->update([
                    'tahun' => $this->year,
                    'nilai_kontrak' => $this->kontrakInduk['nilai_kontrak'],
                    'jenis_kontrak' => 'induk',
                    'nomor_kontrak' => $this->kontrakInduk['nomor_kontrak'],
                ]);
            } else {
                // Create
                $this->pers->KontrakInduk()->create([
                    'tahun' => $this->year,
                    'nilai_kontrak' => $this->kontrakInduk['nilai_kontrak'],
                    'jenis_kontrak' => 'induk',
                    'nomor_kontrak' => $this->kontrakInduk['nomor_kontrak'],
                ]);
            }

            // Kontrak APBDP
            if ($this->kontrakAPBDP['id']) {
                // Update
                $this->pers->KontrakAPBDP()->update([
                    'tahun' => $this->year,
                    'nilai_kontrak' => $this->kontrakAPBDP['nilai_kontrak'],
                    'jenis_kontrak' => 'apbdp',
                    'nomor_kontrak' => $this->kontrakAPBDP['nomor_kontrak'],
                ]);
            } else {
                // Create
                $this->pers->KontrakAPBDP()->create([
                    'tahun' => $this->year,
                    'nilai_kontrak' => $this->kontrakAPBDP['nilai_kontrak'],
                    'jenis_kontrak' => 'apbdp',
                    'nomor_kontrak' => $this->kontrakAPBDP['nomor_kontrak'],
                ]);
            }

            DB::commit();
            $this->alert('success', 'Data kontrak berhasil disimpan.');
            $this->mount($this->pers->unique_id); // Refresh data
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'Terjadi kesalahan saat menyimpan data kontrak. Silakan coba lagi.');
            return;
        }
    }
}
