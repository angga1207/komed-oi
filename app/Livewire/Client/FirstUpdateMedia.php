<?php

namespace App\Livewire\Client;

use App\Models\MediaPers;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class FirstUpdateMedia extends Component
{
    use LivewireAlert, WithFileUploads;
    public $pers;
    public $jenisMedia, $input = [];
    public $step = 1;


    function getListeners()
    {
        return [
            'saveData' => 'saveData',
        ];
    }

    function mount()
    {
        $pers = DB::table('pers_profile')
            ->where('user_id', auth()->id())
            ->where('verified_status', null)
            ->first();
        $this->pers = $pers;
        $this->input = collect($pers)->toArray();
        if (!$this->pers) {
            $this->flash('info', 'Menunggu Verifikasi', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Berkas-berkas Media Anda telah diunggah sebelumnya. Silahkan tunggu verifikasi dari admin.',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelButtonText' => '',
                'confirmButtonText' => 'Tutup',
            ], 'client-dashboard');
            return;
        }
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.client.first-update-media');
    }

    function changeJenisMedia($data)
    {
        if ($data == 1) {
            $this->jenisMedia = 'Media Cetak';
            $this->input['jenis_media'] = $this->jenisMedia;
            $this->input['akta_pendirian'] = null;
            $this->input['sk_kemenkumham'] = null;
            $this->input['siup'] = null;
            $this->input['tdp_penerbitan_58130'] = null;
            $this->input['spt_terakhir'] = null;
            $this->input['sp_cakupan_wilayah'] = null;
            $this->input['sp_pimpinan'] = null;
            $this->input['surat_tugas_wartawan'] = null;
        } else if ($data == 2) {
            $this->jenisMedia = 'Media Elektronik';
            $this->input['jenis_media'] = $this->jenisMedia;
            $this->input['akta_pendirian'] = null;
            $this->input['sk_kemenkumham'] = null;
            $this->input['izin_ipp'] = null;
            $this->input['izin_isr'] = null;
            $this->input['siup'] = null;
            $this->input['tdp_penyiaran_60102'] = null;
            $this->input['spt_terakhir'] = null;
            $this->input['sp_cakupan_wilayah'] = null;
            $this->input['sp_pimpinan'] = null;
            $this->input['sk_biro_iklan'] = null;
            $this->input['surat_tugas_wartawan'] = null;
        } else if ($data == 3) {
            $this->jenisMedia = 'Media Siber';
            $this->input['jenis_media'] = $this->jenisMedia;
            $this->input['akta_pendirian'] = null;
            $this->input['sk_kemenkumham'] = null;
            $this->input['siup'] = null;
            $this->input['tdp_penerbitan_63122'] = null;
            $this->input['spt_terakhir'] = null;
            $this->input['situ'] = null;
            $this->input['sk_domisili'] = null;
            $this->input['surat_tugas_wartawan'] = null;
        } else if ($data == 4) {
            $this->jenisMedia = 'Media Sosial';
            $this->input['jenis_media'] = $this->jenisMedia;
            $this->input['akta_pendirian'] = null;
            $this->input['sk_kemenkumham'] = null;
            $this->input['siup'] = null;
            $this->input['tdp_penerbitan_63122'] = null;
            $this->input['spt_terakhir'] = null;
            $this->input['situ'] = null;
            $this->input['sk_domisili'] = null;
            $this->input['surat_tugas_wartawan'] = null;
        }
        $this->step = 2;
        $this->resetErrorBag();
    }

    function updated($field)
    {
        if (in_array($field, [
            'input.file_jumlah_oplah',
            'input.file_status_wartawan',
            'input.file_kompetensi_wartawan',
            'input.file_status_dewan_pers',
            'input.file_terbitan_3_edisi_terakhir',
        ])) {
            $this->validate([
                'input.file_jumlah_oplah' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.file_status_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.file_kompetensi_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.file_status_dewan_pers' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.file_terbitan_3_edisi_terakhir' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.file_jumlah_oplah' => 'Berkas Jumlah Oplah',
                'input.file_status_wartawan' => 'Berkas Status Wartawan',
                'input.file_kompetensi_wartawan' => 'Berkas Kompetensi Wartawan',
                'input.file_status_dewan_pers' => 'Berkas Status Dewan Pers',
                'input.file_terbitan_3_edisi_terakhir' => 'Berkas Terbitan 3 Edisi Terakhir',
            ]);
        }
    }

    function prevStep()
    {
        $this->resetErrorBag();
        $this->step = $this->step - 1;
        if ($this->step == 1) {
            $this->jenisMedia = null;
            $this->mount();
        }
    }

    function nextStep()
    {
        // dd($this->input);
        $this->validate(
            [
                'input.jenis_media' => 'required|in:Media Cetak,Media Elektronik,Media Siber,Media Sosial,Multimedia',
                'input.nama_media' => 'required',
                'input.nama_perusahaan' => 'required|string|max:255',
                'input.alamat_media' => 'required',
                'input.whatsapp' => 'required|numeric',
                'input.email' => 'required|email|unique:pers_profile,email,' . $this->pers->id,
                'input.no_npwp' => 'nullable|numeric',
                'input.nama_bank' => 'nullable|string',
                'input.no_ref_bank' => 'nullable|numeric',
                'input.no_giro_perusahaan' => 'nullable|numeric',
                'input.website' => 'nullable|string',
                'input.jabatan' => 'required|string',
                'input.profil_perusahaan' => 'nullable',

                'input.cakupan_media' => 'required',
                'input.jumlah_oplah' => 'required_if:input.jenis_media,Media Cetak', // Media Cetak Saja
                'input.sebaran_oplah' => 'required_if:input.jenis_media,Media Cetak', // Media Cetak Saja
                'input.status_wartawan' => 'required',
                'input.kompetensi_wartawan' => 'required',
                'input.status_dewan_pers' => 'required',
                'input.kantor' => 'required',
                'input.frekuensi_terbitan' => 'required_if:input.jenis_media,Media Cetak', // Media Cetak Saja
                'input.terbitan_3_edisi_terakhir' => 'required_if:input.jenis_media,Media Cetak', // Media Cetak Saja

                'input.file_jumlah_oplah' => 'nullable|required_if:input.jenis_media,Media Cetak|file|mimes:png,jpeg,jpg,pdf|max:10000', // Media Cetak Saja
                'input.file_status_wartawan' => 'nullable|required_if:input.status_wartawan,Ada Khusus|required_if:input.status_wartawan,Ada Merangkap Kabupaten Lain',
                'input.file_kompetensi_wartawan' => 'nullable|required_if:input.kompetensi_wartawan,Memiliki Sertifikat Kompetensi',
                'input.file_status_dewan_pers' => 'nullable|required_if:input.status_dewan_pers,Terdaftar',
                'input.file_terbitan_3_edisi_terakhir' => 'nullable|required_if:input.terbitan_3_edisi_terakhir,Ada',

                // 'file_status_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                // 'file_kompetensi_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                // 'file_status_dewan_pers' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
                // 'file_terbitan_3_edisi_terakhir' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ],
            [
                'input.jenis_media.in' => 'Jenis media tidak valid.',
                'input.cakupan_media.required_if' => 'Cakupan media wajib diisi.',
                'input.jumlah_oplah.required_if' => 'Jumlah oplah wajib diisi.',
                'input.sebaran_oplah.required_if' => 'Sebaran oplah wajib diisi.',
                'input.status_wartawan.required_if' => 'Status wartawan wajib diisi.',
                'input.kompetensi_wartawan.required_if' => 'Kompetensi wartawan wajib diisi.',
                'input.status_dewan_pers.required_if' => 'Status terdaftar di dewan pers wajib diisi.',
                'input.kantor.required_if' => 'Kantor wajib diisi.',
                'input.frekuensi_terbitan.required_if' => 'Frekuensi terbitan wajib diisi.',
                'input.terbitan_3_edisi_terakhir.required_if' => 'Terbitan 3 edisi terakhir wajib diisi.',

                'input.file_status_wartawan.required_if' => 'Berkas status wartawan wajib diisi.',
                'input.file_kompetensi_wartawan.required_if' => 'Berkas kompetensi wartawan wajib diisi.',
                'input.file_status_dewan_pers.required_if' => 'Berkas status terdaftar di dewan pers wajib diisi.',
                'input.file_terbitan_3_edisi_terakhir.required_if' => 'Berkas terbitan 3 edisi terakhir wajib diisi.',
            ],
            [
                'input.nama_media' => 'Nama Media',
                'input.nama_perusahaan' => 'Nama Perusahaan',
                'input.jenis_media' => 'Jenis Media',
                'input.alamat_media' => 'Alamat Media',
                'input.whatsapp' => 'Nomor Whatsapp',
                'input.email' => 'Email',
                'input.no_npwp' => 'NPWP',
                'input.nama_bank' => 'Nama Bank',
                'input.no_ref_bank' => 'Nomor Referensi Bank',
                'input.no_giro_perusahaan' => 'Nomor Giro Perusahaan',
                'input.website' => 'Website',
                'input.jabatan' => 'Jabatan',
                'input.profil_perusahaan' => 'Profil Perusahaan',

                'input.cakupan_media' => 'Cakupan Media',
                'input.jumlah_oplah' => 'Jumlah Oplah',
                'input.sebaran_oplah' => 'Sebaran Oplah',
                'input.status_wartawan' => 'Status Wartawan',
                'input.kompetensi_wartawan' => 'Kompetensi Wartawan',
                'input.status_dewan_pers' => 'Status Dewan Pers',
                'input.kantor' => 'Kantor',
                'input.frekuensi_terbitan' => 'Frekuensi Terbitan',
                'input.terbitan_3_edisi_terakhir' => 'Terbitan 3 Edisi Terakhir',

                'input.file_jumlah_oplah' => 'Berkas Jumlah Oplah',
                'input.file_status_wartawan' => 'Berkas Status Wartawan',
                'input.file_kompetensi_wartawan' => 'Berkas Kompetensi Wartawan',
                'input.file_status_dewan_pers' => 'Berkas Status Dewan Pers',
                'input.file_terbitan_3_edisi_terakhir' => 'Berkas Terbitan 3 Edisi Terakhir',
            ]
        );

        DB::beginTransaction();
        try {
            $now = now();
            $pers = DB::table('pers_profile')->where('id', $this->pers->id)->first();
            // $newUniqueID = $this->changeUniqueID($pers->unique_id, $this->input['jenis_media']);
            $newUniqueID = MediaPers::generateUniqueIDStatic($this->input['jenis_media']);

            // update pers profile
            DB::table('pers_profile')
                ->where('id', $pers->id)
                ->update([
                    'unique_id' => $newUniqueID,
                    'jenis_media' => $this->input['jenis_media'],
                    'nama_media' => $this->input['nama_media'],
                    'nama_perusahaan' => $this->input['nama_perusahaan'],
                    'alamat_media' => $this->input['alamat_media'],
                    'whatsapp' => $this->input['whatsapp'],
                    'email' => $this->input['email'],
                    'no_npwp' => $this->input['no_npwp'],
                    'nama_bank' => $this->input['nama_bank'],
                    'no_ref_bank' => $this->input['no_ref_bank'],
                    'no_giro_perusahaan' => $this->input['no_giro_perusahaan'],
                    'website' => $this->input['website'],
                    'jabatan' => $this->input['jabatan'],
                    'profil_perusahaan' => $this->input['profil_perusahaan'],
                    'cakupan_media' => $this->input['cakupan_media'],
                    'jumlah_oplah' => $this->input['jumlah_oplah'],
                    'sebaran_oplah' => $this->input['sebaran_oplah'],
                    'status_wartawan' => $this->input['status_wartawan'],
                    'kompetensi_wartawan' => $this->input['kompetensi_wartawan'],
                    'status_dewan_pers' => $this->input['status_dewan_pers'],
                    'kantor' => $this->input['kantor'],
                    'frekuensi_terbitan' => $this->input['frekuensi_terbitan'],
                    'terbitan_3_edisi_terakhir' => $this->input['terbitan_3_edisi_terakhir'],
                    // 'updated_at' => $now,
                    // 'verified_status' => 'pending',
                    // 'verification_deadline' => now()->addDays(7),
                    // 'verified_at' => null
                ]);


            // UPLOAD DATA BASIC START
            if ($this->input['logo_media']) {
                $fileName = 'logo_media_' . $pers->unique_id . '.' . $this->input['logo_media']->extension();
                $this->input['logo_media']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'logo_media' => $path
                    ]);
                $this->input['logo_media'] = null;
            }
            if ($this->input['file_jumlah_oplah']) {
                $fileName = 'jumlah_oplah_' . $pers->unique_id . '.' . $this->input['file_jumlah_oplah']->extension();
                $this->input['file_jumlah_oplah']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_jumlah_oplah' => $path
                    ]);
                $this->input['file_jumlah_oplah'] = null;
            }
            if ($this->input['file_status_wartawan']) {
                $fileName = 'status_wartawan_' . $pers->unique_id . '.' . $this->input['file_status_wartawan']->extension();
                $this->input['file_status_wartawan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_status_wartawan' => $path
                    ]);
                $this->input['file_status_wartawan'] = null;
            }
            if ($this->input['file_kompetensi_wartawan']) {
                $fileName = 'kompetensi_wartawan_' . $pers->unique_id . '.' . $this->input['file_kompetensi_wartawan']->extension();
                $this->input['file_kompetensi_wartawan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_kompetensi_wartawan' => $path
                    ]);
                $this->input['file_kompetensi_wartawan'] = null;
            }
            if ($this->input['file_status_dewan_pers']) {
                $fileName = 'status_dewan_pers_' . $pers->unique_id . '.' . $this->input['file_status_dewan_pers']->extension();
                $this->input['file_status_dewan_pers']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_status_dewan_pers' => $path
                    ]);
                $this->input['file_status_dewan_pers'] = null;
            }
            if ($this->input['file_terbitan_3_edisi_terakhir']) {
                $fileName = 'terbitan_3_edisi_terakhir_' . $pers->unique_id . '.' . $this->input['file_terbitan_3_edisi_terakhir']->extension();
                $this->input['file_terbitan_3_edisi_terakhir']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_terbitan_3_edisi_terakhir' => $path
                    ]);
                $this->input['file_terbitan_3_edisi_terakhir'] = null;
            }
            // UPLOAD DATA BASIC END

            DB::commit();
            $this->step = 3;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function confirmSave()
    {
        if ($this->jenisMedia == 'Media Cetak') {
            $this->validate([
                'input.akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.tdp_penerbitan_58130' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sp_cakupan_wilayah' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sp_pimpinan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.akta_pendirian' => 'Akta Pendirian',
                'input.sk_kemenkumham' => 'SK Kemenkum',
                'input.siup' => 'SIUP',
                'input.tdp_penerbitan_58130' => 'TDP Penerbitan 58130',
                'input.spt_terakhir' => 'SPT Terakhir',
                'input.sp_cakupan_wilayah' => 'SP Cakupan Wilayah',
                'input.sp_pimpinan' => 'SP Pimpinan',
                'input.surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } else if ($this->jenisMedia == 'Media Elektronik') {
            $this->validate([
                'input.akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.izin_ipp' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.izin_isr' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.tdp_penyiaran_60102' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sp_cakupan_wilayah' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sp_pimpinan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_biro_iklan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.akta_pendirian' => 'Akta Pendirian',
                'input.sk_kemenkumham' => 'SK Kemenkum',
                'input.izin_ipp' => 'Izin IPP',
                'input.izin_isr' => 'Izin ISR',
                'input.siup' => 'SIUP',
                'input.tdp_penyiaran_60102' => 'TDP Penyiaran 60102',
                'input.spt_terakhir' => 'SPT Terakhir',
                'input.sp_cakupan_wilayah' => 'SP Cakupan Wilayah',
                'input.sp_pimpinan' => 'SP Pimpinan',
                'input.sk_biro_iklan' => 'SK Biro Iklan',
                'input.surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } else if ($this->jenisMedia == 'Media Siber') {
            $this->validate([
                'input.akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.tdp_penerbitan_63122' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.situ' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_domisili' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.akta_pendirian' => 'Akta Pendirian',
                'input.sk_kemenkumham' => 'SK Kemenkum',
                'input.siup' => 'SIUP',
                'input.tdp_penerbitan_63122' => 'TDP Penerbitan 63122',
                'input.spt_terakhir' => 'SPT Terakhir',
                'input.situ' => 'SITU',
                'input.sk_domisili' => 'SK Domisili',
                'input.surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } else if ($this->jenisMedia == 'Media Sosial') {
            $this->validate([
                'input.akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.tdp_penerbitan_63122' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.situ' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_domisili' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.akta_pendirian' => 'Akta Pendirian',
                'input.sk_kemenkumham' => 'SK Kemenkum',
                'input.siup' => 'SIUP',
                'input.tdp_penerbitan_63122' => 'TDP Penerbitan 63122',
                'input.spt_terakhir' => 'SPT Terakhir',
                'input.situ' => 'SITU',
                'input.sk_domisili' => 'SK Domisili',
                'input.surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } else if ($this->jenisMedia == 'Multimedia') {
            $this->validate([
                'input.akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.tdp_penerbitan_63122' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.situ' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.sk_domisili' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'input.surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'input.akta_pendirian' => 'Akta Pendirian',
                'input.sk_kemenkumham' => 'SK Kemenkum',
                'input.siup' => 'SIUP',
                'input.tdp_penerbitan_63122' => 'TDP Penerbitan 63122',
                'input.spt_terakhir' => 'SPT Terakhir',
                'input.situ' => 'SITU',
                'input.sk_domisili' => 'SK Domisili',
                'input.surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        }

        $this->confirm('Apakah Anda yakin data yang diunggah sudah Benar?', [
            'toast' => false,
            'position' => 'center',
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Batal',
            'confirmButtonText' => 'Iya, Lanjutkan',
            'onConfirmed' => 'saveData'
        ]);
    }

    function saveData()
    {
        DB::beginTransaction();
        try {
            $now = now();
            $pers = DB::table('pers_profile')->where('id', $this->pers->id)->first();

            if ($this->jenisMedia == 'Media Cetak') {
                if ($this->input['akta_pendirian']) {
                    $fileName = 'akta_pendirian_' . $pers->unique_id . '.' . $this->input['akta_pendirian']->extension();
                    $upload = $this->input['akta_pendirian']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sk_kemenkumham']) {
                    $fileName = 'sk_kemenkumham_' . $pers->unique_id . '.' . $this->input['sk_kemenkumham']->extension();
                    $upload = $this->input['sk_kemenkumham']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['siup']) {
                    $fileName = 'siup_' . $pers->unique_id . '.' . $this->input['siup']->extension();
                    $upload = $this->input['siup']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['tdp_penerbitan_58130']) {
                    $fileName = 'tdp_penerbitan_58130_' . $pers->unique_id . '.' . $this->input['tdp_penerbitan_58130']->extension();
                    $upload = $this->input['tdp_penerbitan_58130']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TPD Penerbitan 58130 ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'tdp_penerbitan_58130',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['spt_terakhir']) {
                    $fileName = 'spt_terakhir_' . $pers->unique_id . '.' . $this->input['spt_terakhir']->extension();
                    $upload = $this->input['spt_terakhir']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sp_cakupan_wilayah']) {
                    $fileName = 'sp_cakupan_wilayah_' . $pers->unique_id . '.' . $this->input['sp_cakupan_wilayah']->extension();
                    $upload = $this->input['sp_cakupan_wilayah']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Cakupan Wilayah ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sp_cakupan_wilayah',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sp_pimpinan']) {
                    $fileName = 'sp_pimpinan_' . $pers->unique_id . '.' . $this->input['sp_pimpinan']->extension();
                    $upload = $this->input['sp_pimpinan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Pimpinan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sp_pimpinan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['surat_tugas_wartawan']) {
                    $fileName = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $this->input['surat_tugas_wartawan']->extension();
                    $upload = $this->input['surat_tugas_wartawan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'surat_tugas_wartawan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
            } elseif ($this->jenisMedia == 'Media Elektronik') {
                if ($this->input['akta_pendirian']) {
                    $fileName = 'akta_pendirian_' . $pers->unique_id . '.' . $this->input['akta_pendirian']->extension();
                    $upload = $this->input['akta_pendirian']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sk_kemenkumham']) {
                    $fileName = 'sk_kemenkumham_' . $pers->unique_id . '.' . $this->input['sk_kemenkumham']->extension();
                    $upload = $this->input['sk_kemenkumham']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['izin_ipp']) {
                    $fileName = 'izin_ipp_' . $pers->unique_id . '.' . $this->input['izin_ipp']->extension();
                    $upload = $this->input['izin_ipp']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Izin IPP ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'izin_ipp',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['izin_isr']) {
                    $fileName = 'izin_isr_' . $pers->unique_id . '.' . $this->input['izin_isr']->extension();
                    $upload = $this->input['izin_isr']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Izin ISR ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'izin_isr',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['siup']) {
                    $fileName = 'siup_' . $pers->unique_id . '.' . $this->input['siup']->extension();
                    $upload = $this->input['siup']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['tdp_penyiaran_60102']) {
                    $fileName = 'tdp_penyiaran_60102_' . $pers->unique_id . '.' . $this->input['tdp_penyiaran_60102']->extension();
                    $upload = $this->input['tdp_penyiaran_60102']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TDP Penyiaran 60102 ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'tdp_penyiaran_60102',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['spt_terakhir']) {
                    $fileName = 'spt_terakhir_' . $pers->unique_id . '.' . $this->input['spt_terakhir']->extension();
                    $upload = $this->input['spt_terakhir']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sp_cakupan_wilayah']) {
                    $fileName = 'sp_cakupan_wilayah_' . $pers->unique_id . '.' . $this->input['sp_cakupan_wilayah']->extension();
                    $upload = $this->input['sp_cakupan_wilayah']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Cakupan Wilayah ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sp_cakupan_wilayah',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sp_pimpinan']) {
                    $fileName = 'sp_pimpinan_' . $pers->unique_id . '.' . $this->input['sp_pimpinan']->extension();
                    $upload = $this->input['sp_pimpinan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Pimpinan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sp_pimpinan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sk_biro_iklan']) {
                    $fileName = 'sk_biro_iklan_' . $pers->unique_id . '.' . $this->input['sk_biro_iklan']->extension();
                    $upload = $this->input['sk_biro_iklan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Biro Iklan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sk_biro_iklan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['surat_tugas_wartawan']) {
                    $fileName = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $this->input['surat_tugas_wartawan']->extension();
                    $upload = $this->input['surat_tugas_wartawan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'surat_tugas_wartawan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
            } elseif ($this->jenisMedia == 'Media Elektronik') {
                if ($this->input['akta_pendirian']) {
                    $fileName = 'akta_pendirian_' . $pers->unique_id . '.' . $this->input['akta_pendirian']->extension();
                    $upload = $this->input['akta_pendirian']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sk_kemenkumham']) {
                    $fileName = 'sk_kemenkumham_' . $pers->unique_id . '.' . $this->input['sk_kemenkumham']->extension();
                    $upload = $this->input['sk_kemenkumham']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['siup']) {
                    $fileName = 'siup_' . $pers->unique_id . '.' . $this->input['siup']->extension();
                    $upload = $this->input['siup']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['tdp_penerbitan_63122']) {
                    $fileName = 'tdp_penerbitan_63122_' . $pers->unique_id . '.' . $this->input['tdp_penerbitan_63122']->extension();
                    $upload = $this->input['tdp_penerbitan_63122']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TDP Penerbitan 63122 ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'tdp_penerbitan_63122',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['spt_terakhir']) {
                    $fileName = 'spt_terakhir_' . $pers->unique_id . '.' . $this->input['spt_terakhir']->extension();
                    $upload = $this->input['spt_terakhir']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['situ']) {
                    $fileName = 'situ_' . $pers->unique_id . '.' . $this->input['situ']->extension();
                    $upload = $this->input['situ']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SITU ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'situ',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['sk_domisili']) {
                    $fileName = 'sk_domisili_' . $pers->unique_id . '.' . $this->input['sk_domisili']->extension();
                    $upload = $this->input['sk_domisili']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Domisili' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'sk_domisili',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
                if ($this->input['surat_tugas_wartawan']) {
                    $fileName = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $this->input['surat_tugas_wartawan']->extension();
                    $upload = $this->input['surat_tugas_wartawan']->storeAs('public/pers-files/' . $pers->id, $fileName, 'public');
                    $path = 'storage/public/pers-files/' . $pers->id . '/' . $fileName;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $pers->nama_perusahaan,
                            'file_name' => $fileName,
                            'file_path' => $path,
                            'file_type' => 'surat_tugas_wartawan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
            }


            // calculation tier point
            $tierPoint = 0;
            $tier = 0;

            // cakupan media
            if ($this->input['cakupan_media'] == 'Nasional/Regional') {
                $tierPoint += 80;
            }
            if ($this->input['cakupan_media'] == 'Regional') {
                $tierPoint += 70;
            }
            if ($this->input['cakupan_media'] == 'Kabupaten') {
                $tierPoint += 60;
            }

            // jumlah oplah
            if ($this->input['jumlah_oplah'] == '> 100.000') {
                $tierPoint += 90;
            }
            if ($this->input['jumlah_oplah'] == '25.001-100.000') {
                $tierPoint += 80;
            }
            if ($this->input['jumlah_oplah'] == '10.001-25.000') {
                $tierPoint += 70;
            }
            if ($this->input['jumlah_oplah'] == '1.001-10.000') {
                $tierPoint += 60;
            }
            if ($this->input['jumlah_oplah'] == '< 1.000') {
                $tierPoint += 20;
            }

            // sebaran oplah
            if ($this->input['sebaran_oplah'] == '11-16 Kecamatan') {
                $tierPoint += 80;
            }
            if ($this->input['sebaran_oplah'] == '6-10 Kecamatan') {
                $tierPoint += 70;
            }
            if ($this->input['sebaran_oplah'] == '< 5 Kecamatan') {
                $tierPoint += 60;
            }

            // status wartawan
            if ($this->input['status_wartawan'] == 'Ada Khusus') {
                $tierPoint += 60;
            }
            if ($this->input['status_wartawan'] == 'Ada Merangkap Kabupaten Lain') {
                $tierPoint += 40;
            }
            if ($this->input['status_wartawan'] == 'Tidak Ada') {
                $tierPoint += 20;
            }

            // kompetensi wartawan
            if ($this->input['kompetensi_wartawan'] == 'Memiliki Sertifikat Kompetensi') {
                $tierPoint += 80;
            }
            if ($this->input['kompetensi_wartawan'] == 'Tidak Memiliki') {
                $tierPoint += 30;
            }

            // status dewan pers
            if ($this->input['status_dewan_pers'] == 'Terdaftar') {
                $tierPoint += 80;
            }
            if ($this->input['status_dewan_pers'] == 'Tidak Terdaftar') {
                $tierPoint += 30;
            }

            // kantor
            if ($this->input['kantor'] == 'Ada') {
                $tierPoint += 20;
            }
            if ($this->input['kantor'] == 'Tidak Ada') {
                $tierPoint += 10;
            }

            // frekuensi terbitan
            if ($this->input['frekuensi_terbitan'] == '1 kali sehari') {
                $tierPoint += 80;
            }
            if ($this->input['frekuensi_terbitan'] == '1 kali seminggu') {
                $tierPoint += 70;
            }
            if ($this->input['frekuensi_terbitan'] == '2 mingguan') {
                $tierPoint += 60;
            }
            if ($this->input['frekuensi_terbitan'] == '1 kali sebulan') {
                $tierPoint += 40;
            }

            // terbitan 3 edisi terakhir
            if ($this->input['terbitan_3_edisi_terakhir'] == 'Ada') {
                $tierPoint += 40;
            }
            if ($this->input['terbitan_3_edisi_terakhir'] == 'Tidak Ada') {
                $tierPoint += 20;
            }

            // RUMUS
            $tierPoint = ($tierPoint / (80 +  90 + 80 + 60 + 80 + 80 + 20 + 80 + 40)) * 100;
            $tierPoint = number_format($tierPoint, 0, '.', '.');
            // RUMUS

            // if tierPoint 91 - 100
            if ($tierPoint > 90 && $tierPoint <= 100) {
                $tier = 1;
            }
            // if tierPoint 81 - 90
            if ($tierPoint > 80 && $tierPoint <= 90) {
                $tier = 2;
            }
            // if tierPoint <= 80
            if ($tierPoint <= 80) {
                $tier = 3;
            }
            // calculation tier point

            // update pers profile
            DB::table('pers_profile')
                ->where('id', $pers->id)
                ->update([
                    'tier_point' => $tierPoint,
                    'tier' => $tier,
                    'updated_at' => $now,
                    'verified_status' => 'pending',
                    'verification_deadline' => now()->addDays(7),
                    'verified_at' => null
                ]);

            DB::commit();

            $this->flash('success', 'Unggahan Media', [
                'position' =>  'center',
                'timer' => null,
                'toast' => false,
                'text' => 'Berkas Anda berhasil diunggah. Silahkan tunggu verifikasi dari admin.',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'cancelButtonText' => '',
                'confirmButtonText' => 'Tutup',
            ], 'client-dashboard');
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
