<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaPers;
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
    public $detail = null;
    public $bannedId, $activeId;

    function getListeners()
    {
        return [
            'banned' => 'banned',
            'verificate' => 'verificate',
        ];
    }

    public function render()
    {
        $datas = MediaPers::search($this->search)
            ->where('verified_status', 'pending')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.media.need-verification', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => 'Media Pers yang Perlu Diverifikasi',
            ]);
    }

    function showDetail($id)
    {
        $mediaPers = MediaPers::with('RegisterFiles')->find($id);
        $mediaPers = $mediaPers->toArray();
        $this->detail = $mediaPers;
        // dd($mediaPers);
    }

    function closeModal()
    {
        $this->detail = null;
    }


    function confirmBanned($id = null)
    {
        $this->confirm('Apakah Anda yakin ingin memblokir Media Pers ini?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Blokir',
            'onConfirmed' => 'banned'
        ]);

        if ($this->detail && !$id) {
            $id = $this->detail['id'];
            $this->bannedId = $id;
        } else {
            $this->bannedId = $id;
        }
    }

    function banned()
    {
        $data = MediaPers::find($this->bannedId);

        if ($data) {
            $data->verified_status = 'banned';
            $data->verification_deadline = null;
            $data->save();

            $this->alert('success', 'Media Pers ini berhasil diblokir', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Pers ini berhasil diblokir',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->bannedId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'banned',
            'model' => 'media_pers',
            'endpoint' => 'media/need-approval',
            'payload' => json_encode(request()->all()),
            'message' => 'Memblokir Media Pers dengan Kode Registrasi ' .  $data->unique_id
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }

    function confirmVerification($id = null)
    {
        $this->confirm('Apakah Anda yakin ingin memverifikasi Media Pers ini?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Aktifkan',
            'onConfirmed' => 'verificate'
        ]);
        if ($this->detail && !$id) {
            $id = $this->detail['id'];
            $this->activeId = $id;
        } else {
            $this->activeId = $id;
        }
    }

    function verificate()
    {
        $data = MediaPers::find($this->activeId);

        if ($data) {
            $data->verified_status = 'verified';
            $data->verification_deadline = null;
            $data->save();

            $this->alert('success', 'Media Pers ini berhasil diverifikasi', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Pers ini berhasil diverifikasi',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->activeId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'verified',
            'model' => 'media_pers',
            'endpoint' => 'media/need-approval',
            'payload' => json_encode(request()->all()),
            'message' => 'Memverifikasi Media Pers dengan Kode Registrasi ' .  $data->unique_id
        ];
        DB::table('user_logs')->insert($log);
        // make log end
    }
}
