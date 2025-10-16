<?php

namespace App\Livewire\Admin\Media;

use App\Models\MediaPers;
use App\Models\MediaPersFiles;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use LivewireAlert, WithFileUploads;

    public $detail, $berkas = [];

    function mount($unique_id)
    {
        $detail = MediaPers::where('unique_id', $unique_id)->with('RegisterFiles')->firstOrFail()->toArray();
        // $detail = $detail->toArray();
        $this->detail = $detail;
        // dd($this->detail);
    }

    public function render()
    {
        return view('livewire.admin.media.edit')
            ->layout('layouts.app', [
                'title' => 'Edit Media: ' . $this->detail['nama_perusahaan'],
            ]);
    }

    function save()
    {
        // dd($this->berkas);
        DB::beginTransaction();
        try {
            $media = MediaPers::where('id', $this->detail['id'])->firstOrFail();
            $detailWithoutFiles = collect($this->detail)
                ->except([
                    'register_files',
                    'new_file_jumlah_oplah',
                    'new_file_status_wartawan',
                    'new_file_kompetensi_wartawan',
                    'new_file_status_dewan_pers',
                    'new_file_terbitan_3_edisi_terakhir',
                ])
                ->toArray();
            // dd($this->detail);
            // dd($detailWithoutFiles);

            if (isset($this->detail['new_file_jumlah_oplah'])) {
                $this->handleDynamicFileUpload($this->detail['new_file_jumlah_oplah'], 'file_jumlah_oplah');
                $detailWithoutFiles['file_jumlah_oplah'] = $this->detail['file_jumlah_oplah'];
            }
            if (isset($this->detail['new_file_status_wartawan'])) {
                $this->handleDynamicFileUpload($this->detail['new_file_status_wartawan'], 'file_status_wartawan');
                $detailWithoutFiles['file_status_wartawan'] = $this->detail['file_status_wartawan'];
            }
            if (isset($this->detail['new_file_kompetensi_wartawan'])) {
                $this->handleDynamicFileUpload($this->detail['new_file_kompetensi_wartawan'], 'file_kompetensi_wartawan');
                $detailWithoutFiles['file_kompetensi_wartawan'] = $this->detail['file_kompetensi_wartawan'];
            }
            if (isset($this->detail['new_file_status_dewan_pers'])) {
                $this->handleDynamicFileUpload($this->detail['new_file_status_dewan_pers'], 'file_status_dewan_pers');
                $detailWithoutFiles['file_status_dewan_pers'] = $this->detail['file_status_dewan_pers'];
            }
            if (isset($this->detail['new_file_terbitan_3_edisi_terakhir'])) {
                $this->handleDynamicFileUpload($this->detail['new_file_terbitan_3_edisi_terakhir'], 'file_terbitan_3_edisi_terakhir');
                $detailWithoutFiles['file_terbitan_3_edisi_terakhir'] = $this->detail['file_terbitan_3_edisi_terakhir'];
            }

            $media->update($detailWithoutFiles);

            // dd($this->berkas);
            foreach ($this->berkas as $key => $file) {
                $this->handleDynamicFileUploadBerkas($file, $key);
                $dataBerkas[$key] = $this->berkas[$key];
            }

            DB::commit();
            $this->alert('success', 'Data berhasil disimpan');

            $this->detail['new_file_jumlah_oplah'] = null;
            $this->detail['new_file_status_wartawan'] = null;
            $this->detail['new_file_kompetensi_wartawan'] = null;
            $this->detail['new_file_status_dewan_pers'] = null;
            $this->detail['new_file_terbitan_3_edisi_terakhir'] = null;
            $this->berkas = [];
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', 'Terjadi kesalahan: ' . $th->getMessage());
            dd($th);
            // throw $th;
        }
    }

    function handleDynamicFileUpload($file, $field)
    {
        // dd($file, $field);
        $this->detail[$field] = $file;
        $fileName = $field . $this->detail['unique_id'] . '.' . $file->extension();
        $upload = $file->storeAs('public/pers-files/' . $this->detail['id'], $fileName, 'public');
        $path = 'storage/public/pers-files/' . $this->detail['id'] . '/' . $fileName;
        $this->detail[$field] = $path;
        // unset new_file_*
        unset($this->detail['new_' . $field]);
    }

    function handleDynamicFileUploadBerkas($file, $field)
    {
        $now = now();
        $this->berkas[$field] = $file;
        $fileName = $field . $this->detail['unique_id'] . '.' . $file->extension();
        $upload = $file->storeAs('public/pers-files/' . $this->detail['id'], $fileName, 'public');
        $path = 'storage/public/pers-files/' . $this->detail['id'] . '/' . $fileName;
        $this->berkas[$field] = $path;

        $checkExisting = MediaPersFiles::where('pers_profile_id', $this->detail['id'])
            ->where('file_type', $field)
            ->first();
        if ($checkExisting) {
            // update
            $checkExisting->update([
                'title' => str_replace('_', ' ', ucwords($field)) . ' ' . $this->detail['nama_perusahaan'],
                'file_name' => $fileName,
                'file_path' => $path,
                'updated_at' => $now
            ]);
        } else {
            // insert
            MediaPersFiles::create([
                'pers_profile_id' => $this->detail['id'],
                'title' => str_replace('_', ' ', ucwords($field)) . ' ' . $this->detail['nama_perusahaan'],
                'file_name' => $fileName,
                'file_path' => $path,
                'file_type' => $field,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
