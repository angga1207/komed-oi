<?php

namespace App\Livewire\Admin\MediaOrder;

use App\Models\Agenda;
use App\Models\MediaPers;
use App\Models\User;
use App\Notifications\OrderNotifications;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class CreateManual extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $filterDate = null, $searchMedia;

    public $search;
    public $detail = [], $inputType = 'create';

    public $agendas = [], $isLoading = true;
    public $selectedAgendaID = null, $selectedAgenda = null;
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

        $this->inputType = 'create';
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

    function openWizardAdd($id)
    {
        $this->selectedAgendaID = $id;
        $this->selectedAgenda = DB::table('agendas')->where('id', $id)->first();
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
                }
            }

            foreach ($arrMediaPers as $mediaPers) {
                $orders = DB::table('orders')
                    ->where('agenda_id', $agd->id)
                    ->where('media_id', $mediaPers->id)
                    ->where('jadwalin_bae_id', null)
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
                // $user = User::find($mediaPers->user_id);
                // $token = $user->routeNotificationForFcm();
                // $user->notify(new OrderNotifications($mediaPers, $orders, 'sent', $token, auth()->id()));
                // send notification end
            }

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

    function closeModal()
    {
        $this->detail = null;
        $this->inputType = 'create';

        $this->selectedMediaPers = [];
        $this->selectedAgenda = null;
        $this->selectedAgendaID = null;
        $this->searchMedia = null;
    }

    function _getAgenda($date)
    {
        $this->isLoading = true;
        $agendas = Agenda::where('jadwalin_bae_id', null)
            ->whereDate('tanggal_pelaksanaan', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return $agendas;
    }

    function _initGetMedia()
    {
        $datas = [];
        $dataAgendas = $this->_getAgenda($this->filterDate);

        if (count($dataAgendas) > 0) {
            foreach ($dataAgendas as $agenda) {
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
                        'status' => $ord->status,
                        'created_at' => $ord->created_at,
                        'deadline' => $ord->deadline,
                    ];
                }

                $datas[] = [
                    'id' => $agenda->id,
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
                ];
            }
        }

        $this->agendas = $datas;
        $this->isLoading = false;
    }

    public function render()
    {
        $arrMediaPers = MediaPers::search($this->searchMedia)
            ->where('verified_status', 'verified')
            ->orderBy('tier')
            ->get();

        return view('livewire.admin.media-order.create-manual', [
            // 'agendas' => $datas,
            'arrMediaPers' => $arrMediaPers,
        ])->layout('layouts.app', ['title' => 'Agenda Manual']);
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
