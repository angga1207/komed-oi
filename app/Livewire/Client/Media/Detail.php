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
    public $deleteEviId = null, $detailEvidence = null;
    public $imageInputs = [];

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
        $this->imageInputs = [
            [
                'file' => null,
                'description' => '',
            ]
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
        $this->imageInputs = [
            [
                'file' => null,
                'description' => '',
            ]
        ];
    }

    function addImageInput()
    {
        $this->imageInputs[] = [
            'file' => null,
            'description' => '',
        ];
    }

    function removeImageInput($index)
    {
        unset($this->imageInputs[$index]);
        $this->imageInputs = array_values($this->imageInputs); // Re-index array
    }

    function closeModal()
    {
        $this->input = [
            'type' => null,
            'link' => null,
            'files' => [],
        ];
        $this->imageInputs = [
            [
                'file' => null,
                'description' => '',
            ]
        ];
        $this->detailEvidence = null;
        $this->deleteEviId = null;
    }

    function uploadEvidence()
    {
        // $this->validate([
        //     'input.type' => 'required|in:image,link',
        //     'input.link' => 'nullable|required_if:input.type,link',
        //     'input.files' => 'nullable|array|required_if:input.type,image',
        // ]);

        if ($this->input['type'] == 'image') {
            $this->validate([
                'imageInputs.*.file' => 'required|image|max:2048', // Validate each image file
                'imageInputs.*.description' => 'nullable|string|max:1000', // Validate each description
            ], [
                'imageInputs.*.file.required' => 'File gambar wajib diisi',
                'imageInputs.*.file.image' => 'File harus berupa gambar',
                'imageInputs.*.file.max' => 'Ukuran file maksimal 2MB',
                'imageInputs.*.description.max' => 'Deskripsi maksimal 1000 karakter',
            ]);
        } else {
            $this->validate([
                'input.type' => 'required|in:image,link',
                'input.link' => 'nullable|required_if:input.type,link',
            ]);
        }

        DB::beginTransaction();
        try {
            // check status media order
            $mediaOrder = DB::table('orders')
                ->where('order_code', $this->mediaOrder->order_code)
                ->first();
            if ($mediaOrder && (in_array($mediaOrder->status, ['sent', 'rejected']) == false)) {
                $this->alert('info', 'Gagal Menambah Bukti', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menambah bukti karena status Media Order sudah berubah!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
                return;
            }

            if ($mediaOrder && $mediaOrder->deadline <= now()) {
                $this->alert('info', 'Gagal Menambah Bukti', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menambah bukti karena sudah melewati batas Deadline pelaporan bukti!',
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
                return;
            }


            if ($this->input['type'] == 'image') {
                foreach ($this->imageInputs as $input) {
                    if ($input['file']) {
                        $file = $input['file'];
                        $fileName = $this->mediaOrder->order_code . '-' . time() . rand(1000, 9999) . '.' . $file->extension();
                        $upload = $file->storeAs('public/evidences/' . $this->mediaOrder->id, $fileName, 'public');
                        $path = 'storage/public/evidences/' . $this->mediaOrder->id . '/' . $fileName;

                        DB::table('order_evidences')
                            ->insert([
                                'order_id' => $this->mediaOrder->id,
                                'media_id' => $this->mediaOrder->media_id,
                                'agenda_id' => $this->mediaOrder->agenda_id,
                                'type' => $this->input['type'],
                                'url' => asset($path),
                                'description' => $input['description'] ?? null,
                                'created_at' => now(),
                            ]);
                    }
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
                    'message' => 'Menunggah Bukti Baru di Media Order ' . $this->mediaOrder->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();

                $this->alert('success', 'Unggah Bukti', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Bukti berhasil diunggah ke ' . $this->mediaOrder->order_code,
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
            'text' => 'Apakah Anda yakin ingin menghapus bukti ini?',
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
                $this->alert('info', 'Gagal Menghapus Bukti', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Tidak dapat menghapus bukti karena status Media Order sudah berubah!',
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

            $this->alert('success', 'Hapus Bukti', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Bukti berhasil dihapus dari ' . $this->mediaOrder->order_code,
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
        $this->confirm('Kirimkan Bukti', [
            'text' => 'Apakah Anda yakin ingin mengirimkan bukti ini ke Admin untuk Diverifikasi?',
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
                ->whereIn('status', ['sent', 'rejected'])
                ->first();
            $media = DB::table('pers_profile')
                ->where('id', $mediaOrder->media_id)
                ->first();
            if ($mediaOrder && $media) {
                DB::table('orders')
                    ->where('order_code', $this->mediaOrder->order_code)
                    ->whereIn('status', ['sent', 'rejected'])
                    ->update([
                        'status' => 'review',
                        'updated_at' => $now,
                        'deadline' => null,
                    ]);

                $note = 'Lampiran Bukti dikirim oleh ' . auth()->user()->fullname . ' dari ' . $media->nama_media;

                DB::table('log_order_status')
                    ->insert([
                        'order_id' => $mediaOrder->id,
                        'media_id' => $mediaOrder->media_id,
                        'agenda_id' => $mediaOrder->agenda_id,
                        'status' => 'review',
                        'note' => $note,
                        'user_id' => auth()->id(),
                        'created_at' => $now,
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
                'text' => 'Lampiran Bukti ' . $this->mediaOrder->order_code . ' berhasil dikirim kepada Admin.',
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

    function editEvidence($id)
    {
        $this->detailEvidence = DB::table('order_evidences')
            ->where('id', $id)
            ->first();
    }

    function saveEditedEvidence()
    {
        DB::beginTransaction();
        try {
            DB::table('order_evidences')
                ->where('id', $this->detailEvidence->id)
                ->update([
                    'description' => $this->detailEvidence->description,
                ]);

            DB::commit();

            $this->alert('success', 'Berhasil Disimpan', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Deskripsi Bukti berhasil disimpan.',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'Tutup',
            ]);
            $this->detailEvidence = null;
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
            $this->alert('error', $e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
