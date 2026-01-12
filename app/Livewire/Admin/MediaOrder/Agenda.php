<?php

namespace App\Livewire\Admin\MediaOrder;

use App\Models\Agenda as AgendaModel;
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

class Agenda extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $detail = [], $inputType = 'create';

    #[Url(null, true, false)]
    public $filterDate = null, $searchMedia;
    public $datas = [], $isLoading = true;
    public $rawJadwalinBaeData = [], $selectedJadwalinBaeID = null, $selectedJadwalinBae = null;
    public $selectedAgendaID = null, $selectedAgenda = null;
    public $selectedMediaPers = [];
    public $selectedJenisMedia = "";
    public $jenisMediaList = [];
    public $orderDetails = []; // Store jumlah and satuan for each selected media

    function getListeners()
    {
        return [
            'applyOrder' => 'applyOrder',
            'applyOrderManual' => 'applyOrderManual',
        ];
    }

    function mount()
    {
        if (!$this->filterDate) {
            $this->filterDate = date('Y-m-d');
        }

        $this->jenisMediaList = DB::table('pers_profile')
            ->select('jenis_media')
            ->distinct()
            ->pluck('jenis_media')
            ->toArray();

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
            ->where('jenis_media', $this->selectedJenisMedia)
            ->where('verified_status', 'verified')
            ->orderBy('tier')
            ->get();

        return view('livewire.admin.media-order.agenda', [
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

    function addData()
    {
        $this->resetErrorBag();
        $this->inputType = 'create';
        $this->detail = [
            'id' => null,
            'jadwalin_bae_id' => null,
            'data' => null,
            'nama_acara' => null,
            'lokasi' => null,
            'tanggal_pelaksanaan' => null,
            'tanggal_pelaksanaan_akhir' => null,
            'waktu_pelaksanaan' => null,
            'leading_sector' => null,
        ];
    }

    function saveData()
    {
        if ($this->inputType == 'create') {
            $this->validate([
                'detail.nama_acara' => 'required',
                'detail.lokasi' => 'required',
                'detail.tanggal_pelaksanaan' => 'required',
                'detail.tanggal_pelaksanaan_akhir' => 'required',
                'detail.waktu_pelaksanaan' => 'required',
                'detail.leading_sector' => 'required',
            ], [], [
                'detail.nama_acara' => 'Nama Acara',
                'detail.lokasi' => 'Lokasi',
                'detail.tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
                'detail.tanggal_pelaksanaan_akhir' => 'Tanggal Pelaksanaan Akhir',
                'detail.waktu_pelaksanaan' => 'Waktu Pelaksanaan',
                'detail.leading_sector' => 'Leading Sector',
            ]);

            DB::table('agendas')->insert([
                'data' => '{}',
                'nama_acara' => $this->detail['nama_acara'],
                'lokasi' => $this->detail['lokasi'],
                'tanggal_pelaksanaan' => $this->detail['tanggal_pelaksanaan'],
                'tanggal_pelaksanaan_akhir' => $this->detail['tanggal_pelaksanaan_akhir'],
                'waktu_pelaksanaan' => $this->detail['waktu_pelaksanaan'],
                'leading_sector' => $this->detail['leading_sector'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->alert('success', 'Data berhasil disimpan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil disimpan',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'create',
                'model' => 'agendas',
                'endpoint' => 'a/media-order/create_manual',
                'payload' => json_encode(request()->all()),
                'message' => 'Menambahkan agenda manual baru dengan nama acara ' . $this->detail['nama_acara'],
                'created_at' => now()
            ];
            DB::table('user_logs')->insert($log);
            // make log end
        } else if ($this->inputType == 'update') {
            $this->validate([
                'detail.nama_acara' => 'required',
                'detail.lokasi' => 'required',
                'detail.tanggal_pelaksanaan' => 'required',
                'detail.tanggal_pelaksanaan_akhir' => 'required',
                'detail.waktu_pelaksanaan' => 'required',
                'detail.leading_sector' => 'required',
            ], [], [
                'detail.nama_acara' => 'Nama Acara',
                'detail.lokasi' => 'Lokasi',
                'detail.tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
                'detail.tanggal_pelaksanaan_akhir' => 'Tanggal Pelaksanaan Akhir',
                'detail.waktu_pelaksanaan' => 'Waktu Pelaksanaan',
                'detail.leading_sector' => 'Leading Sector',
            ]);

            DB::table('agendas')->where('id', $this->detail['id'])->update([
                'nama_acara' => $this->detail['nama_acara'],
                'lokasi' => $this->detail['lokasi'],
                'tanggal_pelaksanaan' => $this->detail['tanggal_pelaksanaan'],
                'tanggal_pelaksanaan_akhir' => $this->detail['tanggal_pelaksanaan_akhir'],
                'waktu_pelaksanaan' => $this->detail['waktu_pelaksanaan'],
                'leading_sector' => $this->detail['leading_sector'],
                'updated_at' => now(),
            ]);

            $this->alert('success', 'Data berhasil diupdate', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Data berhasil diupdate',
                'showCancelButton' => true,
                'showConfirmButton' => false,
                'cancelButtonText' => 'Tutup',
                'confirmText' => '',
            ]);

            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'update',
                'model' => 'agendas',
                'endpoint' => 'a/media-order/create_manual',
                'payload' => json_encode(request()->all()),
                'message' => 'Mengupdate agenda manual dengan nama acara ' . $this->detail['nama_acara'],
                'created_at' => now()
            ];

            DB::table('user_logs')->insert($log);
            // make log end
        }

        $this->dispatch('closeModal');
        $this->closeModal();
        $this->isLoading = true;
        $this->_initGetMedia();
    }

    function _initGetMedia()
    {
        $datas = [];
        $dataJadwalinBae = $this->_getAgendaJadwalinBae($this->filterDate);
        $dataAgendas = $this->_getAgendaManual($this->filterDate);
        $this->rawJadwalinBaeData = $dataJadwalinBae;

        // Process JadwalinBae data
        if (count($dataJadwalinBae) > 0) {
            foreach ($dataJadwalinBae as $djb) {
                $orders = [];
                $arrOrders = DB::table('orders')
                    ->where('jadwalin_bae_id', $djb['id'])
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
                        'jumlah' => $ord->jumlah,
                        'satuan' => $ord->satuan,
                        'created_at' => $ord->created_at,
                        'deadline' => $ord->deadline,
                    ];
                }

                $datas[] = [
                    'id' => $djb['id'],
                    'type' => 'jadwalin_bae',
                    'jadwalin_bae_id' => $djb['id'],
                    'agenda_id' => null,
                    'nama_acara' => $djb['nama_acara'] ?? '',
                    'lokasi' => $djb['tempat_pelaksanaan_id'] ?? '',
                    'tanggal_pelaksanaan' => $djb['tanggal_pelaksanaan'] ?? '',
                    'tanggal_pelaksanaan_akhir' => $djb['tanggal_pelaksanaan_akhir'] ?? '',
                    'waktu_pelaksanaan' => $djb['waktu_pelaksanaan'] ?? '',
                    'leading_sector' => $djb['leading_sector'][0] ?? '',
                    'order_count' => count($orders),
                    'orders' => collect($orders)->toArray(),
                    'is_all_sent' => $arrOrders->where('status', 'sent')->count() == $arrOrders->count() ? true : false,
                    'source' => 'jadwalin_bae'
                ];
            }
        }

        // Process Agendas data
        if (count($dataAgendas) > 0) {
            foreach ($dataAgendas as $agenda) {
                // Skip if this agenda ID already exists in the data (from JadwalinBae)
                if (collect($datas)->where('agenda_id', $agenda->id)->count() > 0) {
                    continue;
                }

                $orders = [];
                $arrOrders = DB::table('orders')
                    ->where('agenda_id', $agenda->id)
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
                        'jumlah' => $ord->jumlah,
                        'satuan' => $ord->satuan,
                        'status' => $ord->status,
                        'created_at' => $ord->created_at,
                        'deadline' => $ord->deadline,
                    ];
                }

                $datas[] = [
                    'id' => $agenda->id,
                    'type' => 'manual',
                    'jadwalin_bae_id' => null,
                    'agenda_id' => $agenda->id,
                    'nama_acara' => $agenda->nama_acara ?? '',
                    'lokasi' => $agenda->lokasi ?? '',
                    'tanggal_pelaksanaan' => $agenda->tanggal_pelaksanaan ?? '',
                    'tanggal_pelaksanaan_akhir' => $agenda->tanggal_pelaksanaan_akhir ?? '',
                    'waktu_pelaksanaan' => $agenda->waktu_pelaksanaan ?? '',
                    'leading_sector' => $agenda->leading_sector ?? '',
                    'order_count' => count($orders),
                    'orders' => collect($orders)->toArray(),
                    'is_all_sent' => $arrOrders->where('status', 'sent')->count() == $arrOrders->count() ? true : false,
                    'source' => 'agenda'
                ];
            }
        }

        // Sort combined data by tanggal_pelaksanaan and waktu_pelaksanaan
        $sortedData = collect($datas)->sortBy([
            ['tanggal_pelaksanaan', 'asc'],
            ['waktu_pelaksanaan', 'asc']
        ])->values()->all();

        // Store sorted data in both properties for backward compatibility
        $this->datas = $sortedData;
        $this->isLoading = false;
    }

    function _getAgendaJadwalinBae($date)
    {
        $this->isLoading = true;
        $uri = "https://jadwalinbae.oganilirkab.go.id/api/jadwalKomed?tanggal=" . $date;

        try {
            // Http with Header
            $response = Http::withHeaders([
                'x-api-key' => 'jadwalin_new@2024'
            ])->timeout(10)->get($uri);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();

            if($data == null){
                return [];
            }
            if (isset($data['error']) && $data['error'] != false) {
                return [];
            }
            $data = $data['data'] ?? [];
            return $data;
        } catch (\Exception $e) {
            // Log the error for debugging but don't break the application
            \Log::error('Failed to fetch JadwalinBae data: ' . $e->getMessage());
            return [];
        }
    }

    function _getAgendaManual($date)
    {
        $this->isLoading = true;
        $agendas = AgendaModel::where('jadwalin_bae_id', null)
            ->whereDate('tanggal_pelaksanaan', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return $agendas;
    }

    function openWizardAdd($id)
    {
        $this->selectedJadwalinBaeID = $id;
        $singleDataJadwalinBae = collect($this->rawJadwalinBaeData);
        $singleDataJadwalinBae = $singleDataJadwalinBae->where('id', $id)->first();
        $this->selectedJadwalinBae = $singleDataJadwalinBae;
        // dd($singleDataJadwalinBae);
    }

    function openWizardAddManual($id)
    {
        $this->selectedAgendaID = $id;
        $this->selectedAgenda = DB::table('agendas')->where('id', $id)->first();
    }

    function closeModal()
    {
        $this->selectedMediaPers = [];
        $this->selectedJadwalinBae = null;
        $this->selectedJadwalinBaeID = null;
        $this->searchMedia = null;
        $this->orderDetails = [];
    }

    function addMedia($id)
    {
        if (in_array($id, $this->selectedMediaPers)) {
            $key = array_search($id, $this->selectedMediaPers);
            unset($this->selectedMediaPers[$key]);
            unset($this->orderDetails[$id]);
            return;
        }
        $this->selectedMediaPers[] = $id;

        // Get media info and initialize order details with default satuan based on media type
        $media = DB::table('pers_profile')->where('id', $id)->first();
        $satuan = $this->getDefaultSatuan($media->jenis_media);
        $this->orderDetails[$id] = [
            'jumlah' => 1,
            'satuan' => $satuan,
            'jenis_media' => $media->jenis_media
        ];
    }

    function getDefaultSatuan($jenisMedia)
    {
        switch ($jenisMedia) {
            case 'Media Siber':
                return 'Tayang';
            case 'Media Cetak':
                return 'Terbit';
            case 'Media Elektronik':
                return 'Paket';
            case 'Media Sosial':
                return 'Tayang';
            default:
                return 'Tayang';
        }
    }

    function getSatuanOptions($jenisMedia)
    {
        switch ($jenisMedia) {
            case 'Media Elektronik':
                return ['Paket', 'Tayang'];
            case 'Media Siber':
                return ['Tayang'];
            case 'Media Cetak':
                return ['Terbit'];
            case 'Media Sosial':
                return ['Tayang'];
            default:
                return ['Tayang'];
        }
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

                    // Get jumlah and satuan from orderDetails
                    $jumlah = $this->orderDetails[$mediaPers->id]['jumlah'] ?? 1;
                    $satuan = $this->orderDetails[$mediaPers->id]['satuan'] ?? $this->getDefaultSatuan($mediaPers->jenis_media);

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
                            'jumlah' => $jumlah,
                            'satuan' => $satuan,
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

    function confirmApplyOrderManual()
    {
        $this->confirm('Apakah Anda yakin ingin menambah Media Order ini?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Iya',
            'onConfirmed' => 'applyOrderManual'
        ]);
    }

    function applyOrderManual()
    {
        DB::beginTransaction();
        try {
            $now = now();
            $agenda = $this->selectedAgenda;

            $agd = DB::table('agendas')
                ->where('id', $agenda->id)
                ->first();

            $arrMediaPers = DB::table('pers_profile')
                ->whereIn('id', $this->selectedMediaPers)
                ->get();

            foreach ($arrMediaPers as $mediaPers) {
                $checkExists = DB::table('orders')
                    ->where('media_id', $mediaPers->id)
                    ->where('agenda_id', $agd->id)
                    ->where('jadwalin_bae_id', null)
                    ->first();
                if (!$checkExists) {
                    $orderCode = $this->generateOrderCode();

                    // Get jumlah and satuan from orderDetails
                    $jumlah = $this->orderDetails[$mediaPers->id]['jumlah'] ?? 1;
                    $satuan = $this->orderDetails[$mediaPers->id]['satuan'] ?? $this->getDefaultSatuan($mediaPers->jenis_media);

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
                            'jumlah' => $jumlah,
                            'satuan' => $satuan,
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
            //         ->where('media_id', $mediaPers->id)
            //         ->where('jadwalin_bae_id', null)
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
                'text' => 'Media Order baru berhasil dikirimkan ke ' . $arrMediaPers->count() . ' Media Pers',
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

    function deleteOrder($orderID)
    {
        DB::beginTransaction();
        try {
            $order = DB::table('orders')
                ->where('id', $orderID)
                ->first();
            $mediaPers = DB::table('pers_profile')
                ->where('id', $order->media_id)
                ->first();
            $agd = DB::table('agendas')
                ->where('id', $order->agenda_id)
                ->first();

            DB::table('orders')
                ->where('id', $orderID)
                ->delete();

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'delete',
                'model' => 'media_order',
                'endpoint' => 'media',
                'payload' => json_encode(request()->all()),
                'message' => 'Menghapus Media Order Baru kepada ' . $mediaPers->nama_media,
                'created_at' => now()
            ];
            DB::table('user_logs')->insert($log);
            // make log end

            DB::commit();

            $this->alert('success', 'Media Order', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Media Order berhasil dihapus dari ' . $mediaPers->nama_media,
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

    function sendAllOrder($jadwalinBaeID)
    {
        DB::beginTransaction();
        try {
            $now = now();
            $agd = DB::table('agendas')
                ->where('jadwalin_bae_id', $jadwalinBaeID)
                ->first();

            $arrOrders = DB::table('orders')
                ->where('jadwalin_bae_id', $jadwalinBaeID)
                ->where('status', 'unsent')
                ->get();

            foreach ($arrOrders as $order) {
                $mediaPers = DB::table('pers_profile')
                    ->where('id', $order->media_id)
                    ->first();

                $checkExists = DB::table('log_order_status')
                    ->where('order_id', $order->id)
                    ->first();

                if (!$checkExists) {
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update([
                            'status' => 'sent',
                            'updated_at' => $now,
                        ]);

                    DB::table('log_order_status')
                        ->insert([
                            'order_id' => $order->id,
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
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function sendAllOrderManual($agendaID)
    {
        DB::beginTransaction();
        try {
            $now = now();
            $agd = DB::table('agendas')
                ->where('id', $agendaID)
                ->first();

            $arrOrders = DB::table('orders')
                ->where('agenda_id', $agendaID)
                ->where('status', 'unsent')
                ->get();

            foreach ($arrOrders as $order) {
                $mediaPers = DB::table('pers_profile')
                    ->where('id', $order->media_id)
                    ->first();

                $checkExists = DB::table('log_order_status')
                    ->where('order_id', $order->id)
                    ->first();

                if (!$checkExists) {
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update([
                            'status' => 'sent',
                            'updated_at' => $now,
                        ]);

                    DB::table('log_order_status')
                        ->insert([
                            'order_id' => $order->id,
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
            }
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
