<?php

namespace App\Livewire\Admin\MediaOrder;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Detail extends Component
{
    use LivewireAlert, WithFileUploads;
    public $mediaOrder = null;
    public $input = [
        'status' => null,
        'note' => null,
    ];
    public $addDuration = [
        'days' => 0,
        'hours' => 0,
        'minutes' => 0,
    ];
    public $deleteEviId = null;

    function getListeners()
    {
        return [
            'respondMediaOrder' => 'respondMediaOrder',
            'goAddDuration' => 'goAddDuration',
        ];
    }

    function mount($order_code)
    {
        $mediaOrder = DB::table('orders')->where('order_code', $order_code)->firstOrFail();
        $this->mediaOrder = $mediaOrder;
    }

    public function render()
    {
        $logs = DB::table('log_order_status')
            ->where('order_id', $this->mediaOrder->id)
            ->oldest('created_at')
            ->get();
        $evidences = DB::table('order_evidences')
            ->where('order_id', $this->mediaOrder->id)
            ->oldest('created_at')
            ->get();

        return view('livewire.admin.media-order.detail', [
            'logs' => $logs,
            'evidences' => $evidences,
        ])
            ->layout('layouts.app', [
                'title' => $this->mediaOrder->order_code,
            ]);
    }

    function openRespond()
    {
        $this->input = [
            'status' => null,
            'note' => null,
        ];
    }

    function openAddDuration()
    {
        $this->addDuration = [
            'days' => 0,
            'hours' => 0,
            'minutes' => 0,
        ];
    }

    function closeModal()
    {
        $this->input = [
            'status' => null,
            'note' => null,
        ];
    }

    function comfirmRespondMediaOrder()
    {
        $this->validate([
            'input.status' => 'required|string',
            'input.note' => 'nullable|string|max:500',
        ], [], [
            'input.status' => 'Status',
            'input.note' => 'Pesan',
        ]);

        $this->confirm('Kirimkan Tanggapan', [
            'text' => 'Apakah Anda yakin ingin mengirimkan tanggapan ini?',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Kirim Sekarang',
            'onConfirmed' => 'respondMediaOrder'
        ]);
    }

    function respondMediaOrder()
    {
        DB::beginTransaction();
        try {
            $now = now();
            $mediaOrder = DB::table('orders')
                ->where('order_code', $this->mediaOrder->order_code)
                ->where('status', 'review')
                ->first();
            $media = DB::table('pers_profile')
                ->where('id', $mediaOrder->media_id)
                ->first();
            if ($mediaOrder && $media) {
                if ($this->input['status'] == 'sent') {
                    $note = 'Media Order dikembalikan oleh Admin';
                    DB::table('orders')
                        ->where('order_code', $this->mediaOrder->order_code)
                        ->where('status', 'review')
                        ->update([
                            'status' => 'sent',
                            'updated_at' => $now,
                            'deadline' => Carbon::now()->addDays(3)->isoFormat('Y-MM-DD HH:mm:ss'),
                        ]);

                    DB::table('log_order_status')
                        ->insert([
                            'order_id' => $mediaOrder->id,
                            'media_id' => $mediaOrder->media_id,
                            'agenda_id' => $mediaOrder->agenda_id,
                            'status' => 'sent',
                            'note' => $this->input['note'] ?? $note,
                            'user_id' => auth()->id(),
                        ]);

                    // make log start
                    $log = [
                        'id' => uniqid(),
                        'user_id' => auth()->id(),
                        'action' => 'sent-back',
                        'model' => 'media_order',
                        'endpoint' => 'media-order',
                        'payload' => json_encode(request()->all()),
                        'message' => 'Media Order ' . $this->mediaOrder->order_code . ' telah direview dan dikembalikan oleh Admin.',
                        'created_at' => now()
                    ];
                    DB::table('user_logs')->insert($log);
                    // make log end
                } elseif ($this->input['status'] == 'verified') {
                    $note = 'Media Order telah diverifikasi oleh Admin';
                    DB::table('orders')
                        ->where('order_code', $this->mediaOrder->order_code)
                        ->where('status', 'review')
                        ->update([
                            'status' => 'verified',
                            'updated_at' => $now,
                        ]);

                    DB::table('log_order_status')
                        ->insert([
                            'order_id' => $mediaOrder->id,
                            'media_id' => $mediaOrder->media_id,
                            'agenda_id' => $mediaOrder->agenda_id,
                            'status' => 'verified',
                            'note' => $this->input['note'] ?? $note,
                            'user_id' => auth()->id(),
                        ]);

                    // make log start
                    $log = [
                        'id' => uniqid(),
                        'user_id' => auth()->id(),
                        'action' => 'verified',
                        'model' => 'media_order',
                        'endpoint' => 'media-order',
                        'payload' => json_encode(request()->all()),
                        'message' => 'Media Order ' . $this->mediaOrder->order_code . ' telah diverifikasi oleh Admin.',
                        'created_at' => now()
                    ];
                    DB::table('user_logs')->insert($log);
                    // make log end
                } elseif ($this->input['status'] == 'done') {
                    $note = 'Media Order telah diverifikasi dan diselesaikan oleh Admin';
                    DB::table('orders')
                        ->where('order_code', $this->mediaOrder->order_code)
                        ->where('status', 'review')
                        ->update([
                            'status' => 'done',
                            'updated_at' => $now,
                        ]);

                    DB::table('log_order_status')
                        ->insert([
                            'order_id' => $mediaOrder->id,
                            'media_id' => $mediaOrder->media_id,
                            'agenda_id' => $mediaOrder->agenda_id,
                            'status' => 'done',
                            'note' => $this->input['note'] ?? $note,
                            'user_id' => auth()->id(),
                        ]);

                    // make log start
                    $log = [
                        'id' => uniqid(),
                        'user_id' => auth()->id(),
                        'action' => 'done',
                        'model' => 'media_order',
                        'endpoint' => 'media-order',
                        'payload' => json_encode(request()->all()),
                        'message' => 'Media Order ' . $this->mediaOrder->order_code . ' telah diverifikasi dan diselesaikan oleh Admin.',
                        'created_at' => now()
                    ];
                    DB::table('user_logs')->insert($log);
                    // make log end
                }
            }

            DB::commit();

            $this->alert('success', 'Berhasil Dikirim', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Lampiran Evidence ' . $this->mediaOrder->order_code . ' berhasil dikirim kepada Admin.',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);
            $this->input = [
                'status' => null,
                'note' => null,
            ];
            $this->dispatch('closeModal');
            $this->mount($mediaOrder->order_code);
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function confirmAddDuration()
    {
        $this->validate([
            'addDuration.days' => 'required|numeric|min:1|max:3',
            'addDuration.hours' => 'nullable|numeric|min:0|max:60',
            'addDuration.minutes' => 'nullable|numeric|min:0|max:60',
        ], [], [
            'addDuration.days' => 'hari',
            'addDuration.hours' => 'Jam',
            'addDuration.minutes' => 'Menit',
        ]);

        $this->confirm('Tambah Durasi Deadline', [
            'text' => 'Apakah Anda yakin menambah durasi Deadline pada Media Order ini?',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Ya, Tambahkan',
            'onConfirmed' => 'goAddDuration'
        ]);
    }

    function goAddDuration()
    {
        DB::beginTransaction();
        try {
            $now = now();
            $mediaOrder = DB::table('orders')
                ->where('order_code', $this->mediaOrder->order_code)
                ->where('status', 'sent')
                ->first();
            $media = DB::table('pers_profile')
                ->where('id', $mediaOrder->media_id)
                ->first();
            $note = 'Durasi deadline Media Order telah ditambahkan oleh Admin sebanyak ' . $this->addDuration['days'] . ' hari';
            if ($mediaOrder && $media) {
                DB::table('orders')
                    ->where('order_code', $this->mediaOrder->order_code)
                    ->where('status', 'sent')
                    ->update([
                        'updated_at' => $now,
                        'deadline' => Carbon::now()->addDays((int)$this->addDuration['days'])->isoFormat('Y-MM-DD HH:mm:ss'),
                    ]);

                DB::table('log_order_status')
                    ->insert([
                        'order_id' => $mediaOrder->id,
                        'media_id' => $mediaOrder->media_id,
                        'agenda_id' => $mediaOrder->agenda_id,
                        'status' => 'sent',
                        'note' => $this->input['note'] ?? $note,
                        'user_id' => auth()->id(),
                    ]);

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'sent-back',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => $note,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end
            }

            DB::commit();

            $this->alert('success', 'Berhasil Ditambahkan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => $note,
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);
            $this->addDuration = [
                'days' => 0,
                'hours' => 0,
                'minutes' => 0,
            ];
            $this->dispatch('closeModal');
            $this->mount($mediaOrder->order_code);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
