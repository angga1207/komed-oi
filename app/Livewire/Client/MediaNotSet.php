<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MediaNotSet extends Component
{
    use LivewireAlert;

    public $isHavePers = false;

    function getListeners()
    {
        return [
            'goToUpdateMedia' => 'goToUpdateMedia',
        ];
    }

    function mount()
    {
        $pers = DB::table('pers_profile')
            ->where('user_id', auth()->id())
            ->first();
        if (!$pers) {
            $this->flash('error', 'Terjadi Kesalahan', [
                'position' =>  'center',
                'timer' =>  null,
                'toast' =>  false,
                'text' =>  'Data Pers Tidak Ditemukan. Harap Daftar Ulang!',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  true,
                'confirmButtonText' =>  'Tutup',
            ], 'register');
            return;
        }
        if ($pers) {
            if ($pers->verified_status == null) {
                $this->isHavePers = false;
                $this->confirm('Data Media Pers Belum Lengkap', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Harap lengkapi Data Media Pers Anda terlebih dahulu untuk melanjutkan!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'cancelButtonText' => '',
                    'confirmButtonText' => 'Lengkapi Sekarang',
                    'onConfirmed' => 'goToUpdateMedia',
                    'allowOutsideClick' => false,
                ]);
            } else if ($pers->verified_status !== null) {
                $this->isHavePers = true;
            }
        }
    }

    function goToUpdateMedia()
    {
        return redirect()->route('firstUpdateMedia');
    }

    public function render()
    {
        return view('livewire.client.media-not-set');
    }
}
