<?php

namespace App\Livewire\Admin\Media;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class NeedVerification extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $search;

    public function render()
    {
        $datas = DB::table('pers_profile')
            ->where('verified_status', 'pending')
            ->where('nama_perusahaan', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.media.need-verification', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => 'Media Pers yang Perlu Diverifikasi',
            ]);
    }
}
