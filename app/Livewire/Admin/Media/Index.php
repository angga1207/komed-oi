<?php

namespace App\Livewire\Admin\Media;

use Livewire\Component;
use App\Models\MediaPers;
use App\Models\User;
use App\Notifications\OrderNotifications;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $search;
    public $detail = null, $filterJenisMedia;
    public $selectMedia = null, $arrAgenda = [], $filterAgendaDate = null;
    public $selecteAgendaToOrder = [];

    function getListeners()
    {
        return [
            'applyOrder' => 'applyOrder',
        ];
    }

    function mount()
    {
        $this->filterAgendaDate = date('Y-m-d');
        $this->generateUniqueId();
    }

    function generateUniqueId()
    {
        $datas = MediaPers::get();
        foreach ($datas as $data) {
            // $oldUniqueId = $data->unique_id;

            if ($data->jenis_media == 'Media Cetak') {
                $jenisMedia = '01';
            } elseif ($data->jenis_media == 'Media Elektronik') {
                $jenisMedia = '02';
            } elseif ($data->jenis_media == 'Media Siber') {
                $jenisMedia = '03';
            } elseif ($data->jenis_media == 'Media Sosial') {
                $jenisMedia = '04';
            } elseif ($data->jenis_media == 'Multimedia') {
                $jenisMedia = '05';
            } else {
                $jenisMedia = '00';
            }

            $format = $jenisMedia . '-' . date('my') . '-';
            $lastRecord = DB::table('pers_profile')
                ->where('unique_id', 'like', $format . '%')
                ->orderBy('id', 'desc')
                ->first();
            if ($lastRecord) {
                $lastId = (int)substr($lastRecord->unique_id, -3) + 1;
            } else {
                $lastId = 1;
            }

            $unique_id = $format . str_pad($lastId, 3, '0', STR_PAD_LEFT);
            if ($this->checkUniqueIDExists($unique_id) == false) {
                $this->generateUniqueID();
            }
            DB::table('pers_profile')
                ->where('id', $data->id)
                ->update([
                    'unique_id' => $unique_id
                ]);
        }
        // dd($datas[0]);
    }

    public function checkUniqueIDExists($unique_id)
    {
        $pers = DB::table('pers_profile')->where('unique_id', $unique_id)->first();
        if ($pers) {
            return false;
        } else {
            return true;
        }
    }

    public function render()
    {
        $datas = MediaPers::search($this->search)
            ->when($this->filterJenisMedia, function ($query, $filterJenisMedia) {
                return $query->where('jenis_media', $filterJenisMedia);
            })
            ->where('verified_status', 'verified')
            // ->latest()
            ->orderBy('unique_id', 'asc')
            ->paginate(10);

        return view('livewire.admin.media.index', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => 'Daftar Media Pers',
            ]);
    }

    function goSearch()
    {
        $this->resetPage();
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
        $this->selectMedia = null;
        $this->selecteAgendaToOrder = [];
    }

    function updated($field)
    {
        if ($field == 'filterAgendaDate') {
            $this->arrAgenda = [];
            $this->_getAgenda($this->filterAgendaDate);
        }
    }

    function _getAgenda($date)
    {
        $uri = "https://jadwalinbae.oganilirkab.go.id/api/jadwalKomed?tanggal=" . $date;

        // Http with Header
        $data = Http::withHeaders([
            'x-api-key' => 'jadwalin_new@2024'
        ])->get($uri);
        $data = $data->body();
        $data = json_decode($data, true);
        // dd($data);

        if ($data['error'] != false) {
            return [
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ];
        }
        $data = $data['data'];
        $this->arrAgenda = $data;
    }

    function wizardAddOrder($id)
    {
        $this->selectMedia = DB::table('pers_profile')
            ->where('id', $id)
            ->first();
        $this->_getAgenda($this->filterAgendaDate);
    }

    function addOrder($id)
    {
        if (in_array($id, $this->selecteAgendaToOrder)) {
            $key = array_search($id, $this->selecteAgendaToOrder);
            unset($this->selecteAgendaToOrder[$key]);
            return;
        }
        $this->selecteAgendaToOrder[] = $id;
    }

    function confirmApplyOrder()
    {
        $this->confirm('Apakah Anda yakin ingin mengirimkan Media Order ini?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Iya',
            'onConfirmed' => 'applyOrder'
        ]);
    }

    function applyOrder()
    {
        DB::beginTransaction();
        try {
            $now = now();
            $mediaPers = MediaPers::find($this->selectMedia->id);
            $agendas = collect($this->arrAgenda)->whereIn('id', $this->selecteAgendaToOrder)->toArray();

            foreach ($agendas as $agd) {
                $checkExists = DB::table('agendas')
                    ->where('jadwalin_bae_id', $agd['id'])
                    ->first();
                if (!$checkExists) {
                    DB::table('agendas')->insert([
                        'jadwalin_bae_id' => (int)$agd['id'],
                        'data' => json_encode($agd),
                        'nama_acara' => $agd['nama_acara'],
                        'lokasi' => $agd['tempat_pelaksanaan_id'],
                        'tanggal_pelaksanaan' => $agd['tanggal_pelaksanaan'],
                        'tanggal_pelaksanaan_akhir' => $agd['tanggal_pelaksanaan_akhir'],
                        'waktu_pelaksanaan' => $agd['waktu_pelaksanaan'],
                        'leading_sector' => $agd['leading_sector'][0] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // MAKE ORDERS START
            $agendas = DB::table('agendas')
                ->whereIn('jadwalin_bae_id', $this->selecteAgendaToOrder)
                ->get();
            if (count($agendas) > 0) {
                foreach ($agendas as $agd) {
                    $checkExists = DB::table('orders')
                        ->where('media_id', $mediaPers->id)
                        ->where('agenda_id', $agd->id)
                        ->where('jadwalin_bae_id', $agd->jadwalin_bae_id)
                        ->first();
                    if (!$checkExists) {
                        $orderCode = $this->generateOrderCode();
                        $orderID = DB::table('orders')
                            ->insertGetId([
                                'order_code' => $orderCode,
                                'media_id' => $mediaPers->id,
                                'agenda_id' => $agd->id,
                                'jadwalin_bae_id' => $agd->jadwalin_bae_id,
                                'nama_acara' => $agd->nama_acara,
                                'lokasi' => $agd->lokasi,
                                'tanggal_pelaksanaan' => $agd->tanggal_pelaksanaan,
                                'tanggal_pelaksanaan_akhir' => $agd->tanggal_pelaksanaan_akhir,
                                'waktu_pelaksanaan' => $agd->waktu_pelaksanaan,
                                'leading_sector' => $agd->leading_sector,
                                'deadline' => Carbon::parse($now)->addDays(7)->isoFormat('Y-MM-DD HH:mm:ss'),
                                'status' => 'sent',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);

                        DB::table('log_order_status')
                            ->insert([
                                'order_id' => $orderID,
                                'media_id' => $mediaPers->id,
                                'agenda_id' => $agd->id,
                                'jadwalin_bae_id' => $agd->jadwalin_bae_id,
                                'status' => 'sent',
                                'note' => 'Media Order dikirim oleh Admin kepada ' . $mediaPers->nama_media,
                                'user_id' => auth()->id(),
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                    } else {
                        $this->alert('info', 'Mohon Maaf', [
                            'position' =>  'center',
                            'timer' => null,
                            'toast' => false,
                            'text' => 'Agenda yang dipilih sudah ada untuk Media ini!',
                            'showCancelButton' => false,
                            'showConfirmButton' => true,
                            'confirmButtonText' => 'Tutup',
                        ]);
                        return;
                    }
                }

                $orders = DB::table('orders')
                    ->whereDate('tanggal_pelaksanaan', $this->filterAgendaDate)
                    ->where('media_id', $mediaPers->id)
                    ->where('status', 'sent')
                    ->get();

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'sent',
                    'model' => 'media_order',
                    'endpoint' => 'media',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Mengirimkan Media Order Baru kepada ' . $mediaPers->nama_media,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                // send notification start
                $user = User::find($mediaPers->user_id);
                $token = $user->routeNotificationForFcm();
                $user->notify(new OrderNotifications($mediaPers, $orders, 'sent', $token, auth()->id()));
                // send notification end

                DB::commit();
                $this->alert('success', 'Media Order', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Media Order baru berhasil dikirimkan ke ' . $mediaPers->nama_media,
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);

                $this->dispatch('closeModal');
                $this->selecteAgendaToOrder = [];
                $this->selectMedia = null;
            }
            // MAKE ORDERS END

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }


    function generateOrderCode()
    {
        $order_code = 'ORDER-' . date('dmy') . '-' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        if ($this->checkOrderCodeExists($order_code) == false) {
            $this->generateOrderCode();
        }
        return $order_code;
    }

    function checkOrderCodeExists($order_code)
    {
        $pers = DB::table('orders')->where('order_code', $order_code)->first();
        if ($pers) {
            return false;
        } else {
            return true;
        }
    }
}
