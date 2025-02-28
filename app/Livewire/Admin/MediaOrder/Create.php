<?php

namespace App\Livewire\Admin\MediaOrder;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\MediaPers;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Notifications\OrderNotifications;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $filterDate = null, $searchMedia;
    public $datas = [], $isLoading = true;
    public $rawJadwalinBaeData = [], $selectedJadwalinBaeID = null, $selectedJadwalinBae = null;
    public $selectedMediaPers = [];

    function getListeners()
    {
        return [
            'applyOrder' => 'applyOrder',
        ];
    }

    function mount()
    {
        if (!$this->filterDate) {
            $this->filterDate = date('Y-m-d');
        }
        // $this->filterDate = '2025-02-11';
    }

    function goSearch()
    {
        //
    }

    public function render()
    {
        // $datas = [];
        $arrMediaPers = MediaPers::search($this->searchMedia)
            ->where('verified_status', 'verified')
            ->orderBy('tier')
            ->get();

        return view('livewire.admin.media-order.create', [
            // 'datas' => $datas,
            'arrMediaPers' => $arrMediaPers,
        ])
            ->layout('layouts.app', [
                'title' => "Buat Media Order"
            ]);
    }

    function updated($field)
    {
        if ($field == 'filterDate') {
            $this->isLoading = true;
            $this->_initGetMedia();
        }
    }

    function _initGetMedia()
    {
        $datas = [];
        $dataJadwalinBae = $this->_getAgenda($this->filterDate);
        $this->rawJadwalinBaeData = $dataJadwalinBae;
        if (count($dataJadwalinBae) > 0) {
            foreach ($dataJadwalinBae as $djb) {
                $orders = [];
                $arrOrders = DB::table('orders')
                    ->where('jadwalin_bae_id', $djb['id'])
                    // ->whereIn('status', ['sent'])
                    ->get();
                foreach ($arrOrders as $ord) {
                    $mediaPers = DB::table('pers_profile')->where('id', $ord->media_id)->first();
                    $orders[] = [
                        'id' => $ord->id,
                        'order_code' => $ord->order_code,
                        'media_id' => $ord->media_id,
                        'nama_media' => $mediaPers->nama_media,
                        'agenda_id' => $ord->agenda_id,
                        'jadwalin_bae_id' => $ord->jadwalin_bae_id,
                        'nama_acara' => $ord->nama_acara,
                        'lokasi' => $ord->lokasi,
                        'tanggal_pelaksanaan' => $ord->tanggal_pelaksanaan,
                        'tanggal_pelaksanaan_akhir' => $ord->tanggal_pelaksanaan_akhir,
                        'waktu_pelaksanaan' => $ord->waktu_pelaksanaan,
                        'leading_sector' => $ord->leading_sector,
                        'status' => $ord->status,
                        'created_at' => $ord->created_at,
                        'deadline' => $ord->deadline,
                    ];
                }
                $datas[] = [
                    'jadwalin_bae_id' => $djb['id'],
                    'nama_acara' => $djb['nama_acara'] ?? '',
                    'lokasi' => $djb['tempat_pelaksanaan_id'] ?? '',
                    'tanggal_pelaksanaan' => $djb['tanggal_pelaksanaan'] ?? '',
                    'tanggal_pelaksanaan_akhir' => $djb['tanggal_pelaksanaan_akhir'] ?? '',
                    'waktu_pelaksanaan' => $djb['waktu_pelaksanaan'] ?? '',
                    'leading_sector' => $djb['leading_sector'][0] ?? '',
                    'order_count' => count($orders),
                    'orders' => collect($orders)->toArray(),
                ];
            }
        }

        $this->datas = $datas;
        $this->isLoading = false;
    }

    function _getAgenda($date)
    {
        $this->isLoading = true;
        $uri = "https://jadwalinbae.oganilirkab.go.id/api/jadwalKomed?tanggal=" . $date;

        // Http with Header
        $data = Http::withHeaders([
            'x-api-key' => 'jadwalin_new@2024'
        ])->get($uri);
        $data = $data->body();
        $data = json_decode($data, true);
        if ($data['error'] != false) {
            return [];
        }
        $data = $data['data'];
        return $data;
    }

    function openWizardAdd($id)
    {
        $this->selectedJadwalinBaeID = $id;
        $singleDataJadwalinBae = collect($this->rawJadwalinBaeData);
        $singleDataJadwalinBae = $singleDataJadwalinBae->where('id', $id)->first();
        $this->selectedJadwalinBae = $singleDataJadwalinBae;
        // dd($singleDataJadwalinBae);
    }

    function closeModal()
    {
        $this->selectedMediaPers = [];
        $this->selectedJadwalinBae = null;
        $this->selectedJadwalinBaeID = null;
        $this->searchMedia = null;
    }

    function addMedia($id)
    {
        if (in_array($id, $this->selectedMediaPers)) {
            $key = array_search($id, $this->selectedMediaPers);
            unset($this->selectedMediaPers[$key]);
            return;
        }
        $this->selectedMediaPers[] = $id;
    }

    function confirmApplyOrder()
    {
        $this->confirm('Apakah Anda yakin ingin menambah Media Order ini?', [
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
            $agenda = $this->selectedJadwalinBae;
            $checkExists = DB::table('agendas')
                ->where('jadwalin_bae_id', $agenda['id'])
                ->first();
            if (!$checkExists) {
                $checkExists = DB::table('agendas')->insertGetId([
                    'jadwalin_bae_id' => (int)$agenda['id'],
                    'data' => json_encode($agenda),
                    'nama_acara' => $agenda['nama_acara'],
                    'lokasi' => $agenda['tempat_pelaksanaan_id'],
                    'tanggal_pelaksanaan' => $agenda['tanggal_pelaksanaan'],
                    'tanggal_pelaksanaan_akhir' => $agenda['tanggal_pelaksanaan_akhir'],
                    'waktu_pelaksanaan' => $agenda['waktu_pelaksanaan'],
                    'leading_sector' => $agenda['leading_sector'][0] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $agd = DB::table('agendas')
                ->where('jadwalin_bae_id', $agenda['id'])
                ->first();

            $arrMediaPers = DB::table('pers_profile')
                ->whereIn('id', $this->selectedMediaPers)
                ->get();

            foreach ($arrMediaPers as $mediaPers) {
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
                            'status' => 'unsent',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                    // DB::table('log_order_status')
                    //     ->insert([
                    //         'order_id' => $orderID,
                    //         'media_id' => $mediaPers->id,
                    //         'agenda_id' => $agd->id,
                    //         'jadwalin_bae_id' => $agd->jadwalin_bae_id,
                    //         'status' => 'sent',
                    //         'note' => 'Media Order dikirim oleh Admin kepada ' . $mediaPers->nama_media,
                    //         'user_id' => auth()->id(),
                    //         'created_at' => $now,
                    //         'updated_at' => $now,
                    //     ]);
                }
            }

            // foreach ($arrMediaPers as $mediaPers) {
            //     $orders = DB::table('orders')
            //         ->where('agenda_id', $agd->id)
            //         ->where('jadwalin_bae_id', $agd->jadwalin_bae_id)
            //         ->where('media_id', $mediaPers->id)
            //         ->where('status', 'sent')
            //         ->get();
            //     // make log start
            //     $log = [
            //         'id' => uniqid(),
            //         'user_id' => auth()->id(),
            //         'action' => 'sent',
            //         'model' => 'media_order',
            //         'endpoint' => 'media',
            //         'payload' => json_encode(request()->all()),
            //         'message' => 'Mengirimkan Media Order Baru kepada ' . $mediaPers->nama_media,
            //         'created_at' => now()
            //     ];
            //     DB::table('user_logs')->insert($log);
            //     // make log end

            //     // send notification start
            //     $user = User::find($mediaPers->user_id);
            //     $token = $user->routeNotificationForFcm();
            //     $user->notify(new OrderNotifications($mediaPers, $orders, 'sent', $token, auth()->id()));
            //     // send notification end
            // }

            DB::commit();

            $this->alert('success', 'Media Order', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Order baru berhasil ditambahkan ke ' . $arrMediaPers->count() . ' Media Pers',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);

            $this->dispatch('closeModal');
            $this->closeModal();
            $this->isLoading = true;
            $this->_initGetMedia();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function sendOrder($orderID)
    {
        DB::beginTransaction();
        try {
            $now = now();
            $order = DB::table('orders')
                ->where('id', $orderID)
                ->first();
            $mediaPers = DB::table('pers_profile')
                ->where('id', $order->media_id)
                ->first();
            $agd = DB::table('agendas')
                ->where('id', $order->agenda_id)
                ->first();

            $checkExists = DB::table('log_order_status')
                ->where('order_id', $orderID)
                ->first();
            if (!$checkExists) {
                DB::table('orders')
                    ->where('id', $orderID)
                    ->update([
                        'status' => 'sent',
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
            }

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
            $user->notify(new OrderNotifications($mediaPers, $order, 'sent', $token, auth()->id()));
            // send notification end

            DB::commit();

            $this->alert('success', 'Media Order', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Order berhasil dikirimkan ke ' . $mediaPers->nama_media,
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);

            $this->isLoading = true;
            $this->_initGetMedia();
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
