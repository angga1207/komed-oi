<?php

namespace App\Livewire\Client\Media;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class Detail extends Component
{
    use LivewireAlert, WithFileUploads;
    public $mediaOrder = null;
    public $input = [];
    public $deleteEviId = null;

    function getListeners()
    {
        return [
            'deleteEvidence' => 'deleteEvidence',
            'sendEvidence' => 'sendEvidence',
        ];
    }

    function mount($order_code)
    {
        $mediaOrder = DB::table('orders')
            ->where('order_code', $order_code)
            ->where('media_id', DB::table('pers_profile')->where('user_id', auth()->id())->first()->id)
            ->firstOrFail();

        $this->mediaOrder = $mediaOrder;
        $this->input = [
            'type' => null,
            'link' => null,
            'files' => [],
        ];
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
        return view('livewire.client.media.detail', [
            'logs' => $logs,
            'evidences' => $evidences,
        ])
            ->layout('layouts.app', [
                'title' => $this->mediaOrder->order_code,
            ]);
    }

    function addEvidence()
    {
        $this->input = [
            'type' => null,
            'link' => null,
            'files' => [],
        ];
    }

    function closeModal()
    {
        $this->input = [
            'type' => null,
            'link' => null,
            'files' => [],
        ];
    }

    function uploadEvidence()
    {
        $this->validate([
            'input.type' => 'required|in:image,link',
            'input.link' => 'nullable|required_if:input.type,link',
            'input.files' => 'nullable|array|required_if:input.type,image',
        ]);

        DB::beginTransaction();
        try {
            // check status media order
            $mediaOrder = DB::table('orders')
                ->where('order_code', $this->mediaOrder->order_code)
                ->first();
            if ($mediaOrder && $mediaOrder->status != 'sent') {
                $this->alert('info', 'Gagal Menambah Evidence', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menambah evidence karena status Media Order sudah berubah!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
                return;
            }

            if ($mediaOrder && $mediaOrder->deadline <= now()) {
                $this->alert('info', 'Gagal Menambah Evidence', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menambah evidence karena sudah melewati batas Deadline pelaporan evidence!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
                return;
            }


            if ($this->input['type'] == 'image') {
                $files = $this->input['files'];
                foreach ($files as $key => $file) {
                    $fileName = $this->mediaOrder->order_code . '-' . time() . $key . '.' . $file->extension();
                    $upload = $file->storeAs('public/evidences/' . $this->mediaOrder->id, $fileName, 'public');
                    $path = 'storage/public/evidences/' . $this->mediaOrder->id . '/' . $fileName;

                    DB::table('order_evidences')
                        ->insert([
                            'order_id' => $this->mediaOrder->id,
                            'media_id' => $this->mediaOrder->media_id,
                            'agenda_id' => $this->mediaOrder->agenda_id,
                            'type' => $this->input['type'],
                            'url' => asset($path),
                        ]);
                }
            } elseif ($this->input['type'] == 'link') {
                DB::table('order_evidences')
                    ->insert([
                        'order_id' => $this->mediaOrder->id,
                        'media_id' => $this->mediaOrder->media_id,
                        'agenda_id' => $this->mediaOrder->agenda_id,
                        'type' => $this->input['type'],
                        'url' => $this->input['link'],
                    ]);
            }

            if ($this->input['type'] == 'image' || $this->input['type'] == 'link') {
                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'upload-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Menunggah Eviden Baru di Media Order ' . $this->mediaOrder->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();

                $this->alert('success', 'Unggah Evidence', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Evidence berhasil diunggah ke ' . $this->mediaOrder->order_code,
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);

                $this->input = [
                    'type' => null,
                    'link' => null,
                    'files' => [],
                ];
                $this->dispatch('closeModal');
                return;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function confirmDeleteEvidence($id)
    {
        $this->confirm('Hapus Evidence', [
            'text' => 'Apakah Anda yakin ingin menghapus evidence ini?',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Hapus',
            'onConfirmed' => 'deleteEvidence'
        ]);
        $this->deleteEviId = $id;
    }

    function deleteEvidence()
    {
        DB::beginTransaction();
        try {
            $id = $this->deleteEviId;
            $data = DB::table('order_evidences')
                ->where('id', $id)
                ->first();

            // check status media order
            $mediaOrder = DB::table('orders')->where('order_code', $this->mediaOrder->order_code)->first();
            if ($mediaOrder && $mediaOrder->status != 'sent') {
                $this->alert('info', 'Gagal Menghapus Evidence', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menghapus evidence karena status Media Order sudah berubah!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
                return;
            }

            if ($data) {
                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'delete-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Menghapus Eviden di Media Order ' . $this->mediaOrder->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::table('order_evidences')
                    ->where('id', $id)
                    ->delete();
            }

            DB::commit();

            $this->alert('success', 'Hapus Evidence', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Evidence berhasil dihapus dari ' . $this->mediaOrder->order_code,
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function comfirmSendEvidence()
    {
        $this->confirm('Kirimkan Evidence', [
            'text' => 'Apakah Anda yakin ingin mengirimkan evidence ini ke Admin untuk Diverifikasi?',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Kirim Sekarang',
            'onConfirmed' => 'sendEvidence'
        ]);
    }

    function sendEvidence()
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
            if ($mediaOrder && $media) {
                DB::table('orders')
                    ->where('order_code', $this->mediaOrder->order_code)
                    ->where('status', 'sent')
                    ->update([
                        'status' => 'review',
                        'updated_at' => $now,
                        'deadline' => null,
                    ]);

                $note = 'Lampiran Evidence dikirim oleh ' . auth()->user()->fullname . ' dari ' . $media->nama_media;

                DB::table('log_order_status')
                    ->insert([
                        'order_id' => $mediaOrder->id,
                        'media_id' => $mediaOrder->media_id,
                        'agenda_id' => $mediaOrder->agenda_id,
                        'status' => 'review',
                        'note' => $note,
                        'user_id' => auth()->id(),
                    ]);


                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'sent-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Mengirimkan Lampiran Eviden kepada Admin pada Media Order ' . $this->mediaOrder->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end
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
            $this->mount($mediaOrder->order_code);
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
            // return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
