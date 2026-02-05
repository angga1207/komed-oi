<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaPers;
use App\Models\User;
use App\Notifications\RegBannedNotification;
use App\Notifications\RegVerifNotification;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NeedVerification extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $search;
    public $detail = null;
    public $bannedId, $resetId, $activeId;

    function getListeners()
    {
        return [
            'banned' => 'banned',
            'verificate' => 'verificate',
            'resetMedia' => 'resetMedia',
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
        $mediaPers = MediaPers::with(['RegisterFiles', 'User'])->find($id);
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
        $this->confirm('Apakah Anda yakin ingin menolak Media Pers ini?', [
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

            $this->alert('success', 'Media Pers ini berhasil ditolak', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Pers ini berhasil ditolak',
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
            'message' => 'Media Pers dengan Kode Registrasi ' .  $data->unique_id . ' ditolak',
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end

        // send notification start
        $user = User::find($data->user_id);
        $token = $user->routeNotificationForFcm();
        $user->notify(new RegBannedNotification($data, $token, auth()->id()));
        // send notification end
    }

    function confirmReset($id = null)
    {
        $this->confirm('Apakah Anda yakin ingin reset Media Pers ini?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Reset',
            'onConfirmed' => 'resetMedia'
        ]);

        if ($this->detail && !$id) {
            $id = $this->detail['id'];
            $this->resetId = $id;
        } else {
            $this->resetId = $id;
        }
    }

    function resetMedia()
    {
        $data = MediaPers::find($this->resetId);

        if ($data) {
            $data->verified_status = null;
            $data->verification_deadline = null;

            // reset all data to null
            $data->jenis_media = null;
            $data->tier_point = 0;
            $data->cakupan_media = null;
            $data->jumlah_oplah = null;
            $data->sebaran_oplah = null;
            $data->status_wartawan = null;
            $data->kompetensi_wartawan = null;
            $data->status_dewan_pers = null;
            $data->kantor = null;
            $data->frekuensi_terbitan = null;
            $data->terbitan_3_edisi_terakhir = null;
            $data->file_jumlah_oplah = null;
            $data->file_status_wartawan = null;
            $data->file_kompetensi_wartawan = null;
            $data->file_status_dewan_pers = null;
            $data->file_terbitan_3_edisi_terakhir = null;
            $data->extend_verification_message = null;

            // delete pers_profile_files
            DB::table('pers_profile_files')->where('pers_profile_id', $data->id)->delete();

            $data->save();

            $this->alert('success', 'Media Pers ini berhasil direset', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Pers ini berhasil direset',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelText' => '',
                'confirmText' => 'Tutup',
            ]);
        }

        $this->resetId = null;

        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => auth()->id(),
            'action' => 'reset',
            'model' => 'media_pers',
            'endpoint' => 'media/need-approval',
            'payload' => json_encode(request()->all()),
            'message' => 'Media Pers dengan Kode Registrasi ' .  $data->unique_id . ' direset',
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end

        // send notification start
        $user = User::find($data->user_id);
        $token = $user->routeNotificationForFcm();
        $user->notify(new RegBannedNotification($data, $token, auth()->id()));
        // send notification end
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
            'message' => 'Memverifikasi Media Pers dengan Kode Registrasi ' .  $data->unique_id,
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end


        // send notification start
        $user = User::find($data->user_id);
        $token = $user->routeNotificationForFcm();
        $user->notify(new RegVerifNotification($data, $token, auth()->id()));
        // send notification end
    }
}
