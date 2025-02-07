<?php

namespace App\Http\Controllers\API;

use App\HeaderChecker;
use App\JsonReturner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use JsonReturner, HeaderChecker;

    function register(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'whatsapp' => 'required|string|numeric|digits_between:11,13|unique:users,whatsapp',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ], [], [
            'fullname' => 'Nama Lengkap',
            'nik' => 'N.I.K',
            'email' => 'Email',
            'whatsapp' => 'Nomor Whatsapp',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password'
        ]);

        if ($validated->fails()) {
            // return $this->validationErrorResponse($validated->errors());
            return $this->validationErrorResponse($validated->errors()->first());
        }

        DB::beginTransaction();
        try {
            $now = now();

            $firstName = explode(' ', $request->fullname)[0];
            $lastName = explode(' ', $request->fullname)[1] ?? '';


            $userCheck = DB::table('users')->where('username', $request->nik)->first();
            if (!$userCheck) {
                $userID = DB::table('users')->insertGetId([
                    'fullname' => $request->fullname,
                    'first_name' => $firstName ?? $request->fullname,
                    'last_name' => $lastName ?? '',
                    'username' => $request->nik,
                    'email' => $request->email,
                    'whatsapp' => $request->whatsapp,
                    'password' => bcrypt($request->password),
                    'photo' => 'storage/images/users/default.png',
                    'role_id' => 4,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $user = User::find($userID);

                // formula unique_id = PERS.xxxx.xxxxx
                $unique_id = $this->generateUniqueID();

                $pers = DB::table('pers_profile')->insert([
                    'user_id' => $user->id,
                    'unique_id' => $unique_id,
                    'nik' => $request->nik,
                    'whatsapp' => $request->whatsapp,
                    'verified_status' => null,
                    'verification_deadline' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $pers = DB::table('pers_profile')->where('user_id', $user->id)->first();

                // make Token
                $token = $user->createToken('authToken')->plainTextToken;

                $returnData = [
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'nik' => $user->username,
                    'username' => $user->username,
                    'whatsapp' => $user->whatsapp,
                    'role_id' => $user->role_id,
                    'role_name' => DB::table('roles')->where('id', $user->role_id)->value('name'),
                    'pers_unique_id' => $pers->unique_id,
                    'token' => $token
                ];

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => $user->id,
                    'action' => 'register',
                    'model' => 'akun',
                    'endpoint' => 'api/register',
                    'payload' => json_encode($request->all()),
                    'message' => 'Melakukan registrasi'
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();
                return $this->successResponse($returnData, 'Registrasi berhasil');
            } else {
                return $this->validationErrorResponse(['nik' => 'N.I.K sudah terdaftar']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }

    function generateUniqueID()
    {
        $unique_id = 'PERS.' . date('my') . '.' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        if ($this->checkUniqueIDExists($unique_id) == false) {
            $this->generateUniqueID();
        }
        return $unique_id;
    }

    function checkUniqueIDExists($unique_id)
    {
        $pers = DB::table('pers_profile')->where('unique_id', $unique_id)->first();
        if ($pers) {
            return false;
        } else {
            return true;
        }
    }

    function getMedia(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $user = User::find(auth()->id());
        $pers = DB::table('pers_profile')
            ->where('user_id', $user->id)
            ->first();

        $return = [
            'id' => $pers->id,
            'jenis_media' => $pers->jenis_media,
            'nama_media' => $pers->nama_media,
            'nama_perusahaan' => $pers->nama_perusahaan,
            'alamat_media' => $pers->alamat_media,
            'whatsapp' => $pers->whatsapp,
            'email' => $pers->email,
            'no_npwp' => $pers->no_npwp,
            'no_ref_bank' => $pers->no_ref_bank,
            'no_giro_perusahaan' => $pers->no_giro_perusahaan,
            'website' => $pers->website,
            'jabatan' => $pers->jabatan,
            'profil_perusahaan' => $pers->profil_perusahaan,
            'cakupan_media' => $pers->cakupan_media,
            'jumlah_oplah' => $pers->jumlah_oplah,
            'sebaran_oplah' => $pers->sebaran_oplah,
            'status_wartawan' => $pers->status_wartawan,
            'kompetensi_wartawan' => $pers->kompetensi_wartawan,
            'status_dewan_pers' => $pers->status_dewan_pers,
            'kantor' => $pers->kantor,
            'frekuensi_terbitan' => $pers->frekuensi_terbitan,
            'terbitan_3_edisi_terakhir' => $pers->terbitan_3_edisi_terakhir,

            'file_jumlah_oplah' => $pers->file_jumlah_oplah ? asset($pers->file_jumlah_oplah) : null,
            'file_status_wartawan' => $pers->file_status_wartawan ? asset($pers->file_status_wartawan) : null,
            'file_kompetensi_wartawan' => $pers->file_kompetensi_wartawan ? asset($pers->file_kompetensi_wartawan) : null,
            'file_status_dewan_pers' => $pers->file_status_dewan_pers ? asset($pers->file_status_dewan_pers) : null,
            'file_terbitan_3_edisi_terakhir' => $pers->file_terbitan_3_edisi_terakhir ? asset($pers->file_terbitan_3_edisi_terakhir) : null,

            'tier_point' => floatval($pers->tier_point ?? 0),
            'tier' => $pers->tier,
            'verified_status' => $pers->verified_status,
            'verification_deadline' => $pers->verification_deadline,
            'verified_at' => $pers->verified_at,
            'created_at' => $pers->created_at,
            'updated_at' => $pers->updated_at,
        ];

        $arrFiles = [];
        $typeFiles = DB::table('pers_profile_files')
            ->where('pers_profile_id', $pers->id)
            ->pluck('file_type');
        foreach ($typeFiles as $type) {
            $file = DB::table('pers_profile_files')
                ->where('pers_profile_id', $pers->id)
                ->where('file_type', $type)
                ->latest('created_at')
                ->first();

            if ($file) {
                $arrFiles[] = [
                    'id' => $file->id,
                    'title' => $file->title,
                    'file_type' => $type,
                    'file_name' => $file->file_name,
                    'file_path' => asset($file->file_path),
                    'created_at' => $file->created_at,
                    'updated_at' => $file->updated_at,
                ];
            }
        }
        $return['files'] = $arrFiles;

        return $this->successResponse($return);
    }

    function updateMedia(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $user = User::find(auth()->id());
        $pers = DB::table('pers_profile')
            ->where('user_id', $user->id)
            ->first();

        $validated = Validator::make($request->all(), [
            'jenis_media' => 'required|in:Media Cetak,Media Elektronik,Media Siber',
            'nama_media' => 'required',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_media' => 'required',
            'whatsapp' => 'required|numeric',
            'email' => 'required|email|unique:pers_profile,email,' . $pers->id,
            'no_npwp' => 'nullable|numeric',
            'no_ref_bank' => 'nullable|numeric',
            'no_giro_perusahaan' => 'nullable|numeric',
            'website' => 'nullable|url',
            'jabatan' => 'required|string',
            'profil_perusahaan' => 'nullable',

            'cakupan_media' => 'required',
            'jumlah_oplah' => 'required',
            'sebaran_oplah' => 'required',
            'status_wartawan' => 'required',
            'kompetensi_wartawan' => 'required',
            'status_dewan_pers' => 'required',
            'kantor' => 'required',
            'frekuensi_terbitan' => 'required',
            'terbitan_3_edisi_terakhir' => 'required',

            'file_jumlah_oplah' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            // 'file_status_wartawan' => 'required_if:status_wartawan,Ada Khusus|required_if:status_wartawan,Ada Merangkap Kabupaten Lain',
            // 'file_kompetensi_wartawan' => 'required_if:kompetensi_wartawan,Memiliki Sertifikat Kompetensi',
            // 'file_status_dewan_pers' => 'required_if:status_dewan_pers,Terdaftar',
            // 'file_terbitan_3_edisi_terakhir' => 'required_if:terbitan_3_edisi_terakhir,Ada',

            'file_status_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
            'file_kompetensi_wartawan' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
            'file_status_dewan_pers' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
            'file_terbitan_3_edisi_terakhir' => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:10000',
        ], [
            'jenis_media.in' => 'Jenis media tidak valid.',
            'cakupan_media.required_if' => 'Cakupan media wajib diisi.',
            'jumlah_oplah.required_if' => 'Jumlah oplah wajib diisi.',
            'sebaran_oplah.required_if' => 'Sebaran oplah wajib diisi.',
            'status_wartawan.required_if' => 'Status wartawan wajib diisi.',
            'kompetensi_wartawan.required_if' => 'Kompetensi wartawan wajib diisi.',
            'status_dewan_pers.required_if' => 'Status terdaftar di dewan pers wajib diisi.',
            'kantor.required_if' => 'Kantor wajib diisi.',
            'frekuensi_terbitan.required_if' => 'Frekuensi terbitan wajib diisi.',
            'terbitan_3_edisi_terakhir.required_if' => 'Terbitan 3 edisi terakhir wajib diisi.',

            'file_status_wartawan.required_if' => 'Berkas status wartawan wajib diisi.',
            'file_kompetensi_wartawan.required_if' => 'Berkas kompetensi wartawan wajib diisi.',
            'file_status_dewan_pers.required_if' => 'Berkas status terdaftar di dewan pers wajib diisi.',
            'file_terbitan_3_edisi_terakhir.required_if' => 'Berkas terbitan 3 edisi terakhir wajib diisi.',
        ], [
            'nama_media' => 'Nama Media',
            'nama_perusahaan' => 'Nama Perusahaan',
            'jenis_media' => 'Jenis Media',
            'alamat_media' => 'Alamat Media',
            'whatsapp' => 'Nomor Whatsapp',
            'email' => 'Email',
            'no_npwp' => 'Nomor NPWP',
            'no_ref_bank' => 'Nomor Referensi Bank',
            'no_giro_perusahaan' => 'Nomor Giro Perusahaan',
            'website' => 'Website',
            'jabatan' => 'Jabatan',
            'profil_perusahaan' => 'Profil Perusahaan',

            'cakupan_media' => 'Cakupan Media',
            'jumlah_oplah' => 'Jumlah Oplah',
            'sebaran_oplah' => 'Sebaran Oplah',
            'status_wartawan' => 'Status Wartawan',
            'kompetensi_wartawan' => 'Kompetensi Wartawan',
            'status_dewan_pers' => 'Status Dewan Pers',
            'kantor' => 'Kantor',
            'frekuensi_terbitan' => 'Frekuensi Terbitan',
            'terbitan_3_edisi_terakhir' => 'Terbitan 3 Edisi Terakhir',

            'file_jumlah_oplah' => 'Berkas Jumlah Oplah',
            'file_status_wartawan' => 'Berkas Status Wartawan',
            'file_kompetensi_wartawan' => 'Berkas Kompetensi Wartawan',
            'file_status_dewan_pers' => 'Berkas Status Dewan Pers',
            'file_terbitan_3_edisi_terakhir' => 'Berkas Terbitan 3 Edisi Terakhir',
        ]);

        if ($validated->fails()) {
            // return $this->validationErrorResponse($validated->errors());
            return $this->validationErrorResponse($validated->errors()->first());
        }

        if ($request->jenis_media == 'Media Cetak') {
            $validated2 = Validator::make($request->all(), [
                'akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'tdp_penerbitan_58130' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sp_cakupan_wilayah' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sp_pimpinan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'akta_pendirian' => 'Akta Pendirian',
                'sk_kemenkumham' => 'SK Kemenkum',
                'siup' => 'SIUP',
                'tdp_penerbitan_58130' => 'TDP Penerbitan 58130',
                'spt_terakhir' => 'SPT Terakhir',
                'sp_cakupan_wilayah' => 'SP Cakupan Wilayah',
                'sp_pimpinan' => 'SP Pimpinan',
                'surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } elseif ($request->jenis_media == 'Media Elektronik') {
            $validated2 = Validator::make($request->all(), [
                'akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'izin_ipp' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'izin_isr' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'tdp_penyiaran_60102' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sp_cakupan_wilayah' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sp_pimpinan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sk_biro_iklan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'akta_pendirian' => 'Akta Pendirian',
                'sk_kemenkumham' => 'SK Kemenkum',
                'izin_ipp' => 'Izin IPP',
                'izin_isr' => 'Izin ISR',
                'siup' => 'SIUP',
                'tdp_penyiaran_60102' => 'TDP Penyiaran 60102',
                'spt_terakhir' => 'SPT Terakhir',
                'sp_cakupan_wilayah' => 'SP Cakupan Wilayah',
                'sp_pimpinan' => 'SP Pimpinan',
                'sk_biro_iklan' => 'SK Biro Iklan',
                'surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        } elseif ($request->jenis_media == 'Media Siber') {
            $validated2 = Validator::make($request->all(), [
                'akta_pendirian' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sk_kemenkumham' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'siup' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'tdp_penerbitan_63122' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'spt_terakhir' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'situ' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'sk_domisili' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
                'surat_tugas_wartawan' => 'required|file|mimes:png,jpeg,jpg,pdf|max:10000',
            ], [], [
                'akta_pendirian' => 'Akta Pendirian',
                'sk_kemenkumham' => 'SK Kemenkum',
                'siup' => 'SIUP',
                'tdp_penerbitan_63122' => 'TDP Penerbitan 63122',
                'spt_terakhir' => 'SPT Terakhir',
                'situ' => 'SITU',
                'sk_domisili' => 'SK Domisili',
                'surat_tugas_wartawan' => 'Surat Tugas Wartawan',
            ]);
        }

        if ($validated2->fails()) {
            // return $this->validationErrorResponse($validated2->errors());
            return $this->validationErrorResponse($validated2->errors()->first());
        }


        DB::beginTransaction();
        try {
            $return = [];
            $now = now();

            // update pers profile
            DB::table('pers_profile')
                ->where('id', $pers->id)
                ->update([
                    'jenis_media' => $request->jenis_media,
                    'nama_media' => $request->nama_media,
                    'nama_perusahaan' => $request->nama_perusahaan,
                    'alamat_media' => $request->alamat_media,
                    'whatsapp' => $request->whatsapp,
                    'email' => $request->email,
                    'no_npwp' => $request->no_npwp,
                    'no_ref_bank' => $request->no_ref_bank,
                    'no_giro_perusahaan' => $request->no_giro_perusahaan,
                    'website' => $request->website,
                    'jabatan' => $request->jabatan,
                    'profil_perusahaan' => $request->profil_perusahaan,
                    'cakupan_media' => $request->cakupan_media,
                    'jumlah_oplah' => $request->jumlah_oplah,
                    'sebaran_oplah' => $request->sebaran_oplah,
                    'status_wartawan' => $request->status_wartawan,
                    'kompetensi_wartawan' => $request->kompetensi_wartawan,
                    'status_dewan_pers' => $request->status_dewan_pers,
                    'kantor' => $request->kantor,
                    'frekuensi_terbitan' => $request->frekuensi_terbitan,
                    'terbitan_3_edisi_terakhir' => $request->terbitan_3_edisi_terakhir,
                    'updated_at' => $now,
                    'verified_status' => 'pending',
                    'verification_deadline' => now()->addDays(7),
                    'verified_at' => null
                ]);

            // upload data basic
            if ($request->hasFile('file_jumlah_oplah')) {
                $fileNameJumlahOplah = 'jumlah_oplah_' . $pers->unique_id . '.' . $request->file('file_jumlah_oplah')->extension();
                $request->file('file_jumlah_oplah')->storeAs('public/pers-files/' . $pers->id, $fileNameJumlahOplah, 'public');
                $pathJumlahOplah = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameJumlahOplah;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_jumlah_oplah' => $pathJumlahOplah
                    ]);
            }
            if ($request->hasFile('file_status_wartawan')) {
                $fileNameStatusWartawan = 'status_wartawan_' . $pers->unique_id . '.' . $request->file('file_status_wartawan')->extension();
                $request->file('file_status_wartawan')->storeAs('public/pers-files/' . $pers->id, $fileNameStatusWartawan, 'public');
                $pathStatusWartawan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameStatusWartawan;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_status_wartawan' => $pathStatusWartawan
                    ]);
            }
            if ($request->hasFile('file_kompetensi_wartawan')) {
                $fileNameKompetensiWartawan = 'kompetensi_wartawan_' . $pers->unique_id . '.' . $request->file('file_kompetensi_wartawan')->extension();
                $request->file('file_kompetensi_wartawan')->storeAs('public/pers-files/' . $pers->id, $fileNameKompetensiWartawan, 'public');
                $pathKompetensiWartawan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameKompetensiWartawan;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_kompetensi_wartawan' => $pathKompetensiWartawan
                    ]);
            }
            if ($request->hasFile('file_status_dewan_pers')) {
                $fileNameStatusDewanPers = 'status_dewan_pers_' . $pers->unique_id . '.' . $request->file('file_status_dewan_pers')->extension();
                $request->file('file_status_dewan_pers')->storeAs('public/pers-files/' . $pers->id, $fileNameStatusDewanPers, 'public');
                $pathStatusDewanPers = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameStatusDewanPers;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_status_dewan_pers' => $pathStatusDewanPers
                    ]);
            }
            if ($request->hasFile('file_terbitan_3_edisi_terakhir')) {
                $fileNameTerbitan3EdisiTerakhir = 'terbitan_3_edisi_terakhir_' . $pers->unique_id . '.' . $request->file('file_terbitan_3_edisi_terakhir')->extension();
                $request->file('file_terbitan_3_edisi_terakhir')->storeAs('public/pers-files/' . $pers->id, $fileNameTerbitan3EdisiTerakhir, 'public');
                $pathTerbitan3EdisiTerakhir = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameTerbitan3EdisiTerakhir;

                DB::table('pers_profile')
                    ->where('id', $pers->id)
                    ->update([
                        'file_terbitan_3_edisi_terakhir' => $pathTerbitan3EdisiTerakhir
                    ]);
            }

            // upload file jika media cetak
            if ($request->jenis_media == 'Media Cetak') {
                if ($request->hasFile('akta_pendirian')) {
                    $fileNameAktaPendirian = 'akta_pendirian_' . $pers->unique_id . '.' . $request->file('akta_pendirian')->extension();
                    $request->file('akta_pendirian')->storeAs('public/pers-files/' . $pers->id, $fileNameAktaPendirian, 'public');
                    $pathAktaPendirian = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameAktaPendirian;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $request->nama_perusahaan,
                            'file_name' => $fileNameAktaPendirian,
                            'file_path' => $pathAktaPendirian,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sk_kemenkumham')) {
                    $fileNameSkKemenkumham = 'sk_kemenkumham_' . $pers->unique_id . '.' . $request->file('sk_kemenkumham')->extension();
                    $request->file('sk_kemenkumham')->storeAs('public/pers-files/' . $pers->id, $fileNameSkKemenkumham, 'public');
                    $pathSkKemenkumham = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSkKemenkumham;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSkKemenkumham,
                            'file_path' => $pathSkKemenkumham,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('siup')) {
                    $fileNameSiup = 'siup_' . $pers->unique_id . '.' . $request->file('siup')->extension();
                    $request->file('siup')->storeAs('public/pers-files/' . $pers->id, $fileNameSiup, 'public');
                    $pathSiup = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSiup;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSiup,
                            'file_path' => $pathSiup,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('tdp_penerbitan_58130')) {
                    $fileNameTdpPenerbitan58130 = 'tdp_penerbitan_58130_' . $pers->unique_id . '.' . $request->file('tdp_penerbitan_58130')->extension();
                    $request->file('tdp_penerbitan_58130')->storeAs('public/pers-files/' . $pers->id, $fileNameTdpPenerbitan58130, 'public');
                    $pathTdpPenerbitan58130 = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameTdpPenerbitan58130;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TDP Penerbitan 58130 ' . $request->nama_perusahaan,
                            'file_name' => $fileNameTdpPenerbitan58130,
                            'file_path' => $pathTdpPenerbitan58130,
                            'file_type' => 'tdp_penerbitan_58130',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('spt_terakhir')) {
                    $fileNameSptTerakhir = 'spt_terakhir_' . $pers->unique_id . '.' . $request->file('spt_terakhir')->extension();
                    $request->file('spt_terakhir')->storeAs('public/pers-files/' . $pers->id, $fileNameSptTerakhir, 'public');
                    $pathSptTerakhir = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSptTerakhir;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSptTerakhir,
                            'file_path' => $pathSptTerakhir,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sp_cakupan_wilayah')) {
                    $fileNameSpCakupanWilayah = 'sp_cakupan_wilayah_' . $pers->unique_id . '.' . $request->file('sp_cakupan_wilayah')->extension();
                    $request->file('sp_cakupan_wilayah')->storeAs('public/pers-files/' . $pers->id, $fileNameSpCakupanWilayah, 'public');
                    $pathSpCakupanWilayah = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSpCakupanWilayah;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Cakupan Wilayah ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSpCakupanWilayah,
                            'file_path' => $pathSpCakupanWilayah,
                            'file_type' => 'sp_cakupan_wilayah',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sp_pimpinan')) {
                    $fileNameSpPimpinan = 'sp_pimpinan_' . $pers->unique_id . '.' . $request->file('sp_pimpinan')->extension();
                    $request->file('sp_pimpinan')->storeAs('public/pers-files/' . $pers->id, $fileNameSpPimpinan, 'public');
                    $pathSpPimpinan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSpPimpinan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Pimpinan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSpPimpinan,
                            'file_path' => $pathSpPimpinan,
                            'file_type' => 'sp_pimpinan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('surat_tugas_wartawan')) {
                    $fileNameSuratTugasWartawan = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $request->file('surat_tugas_wartawan')->extension();
                    $request->file('surat_tugas_wartawan')->storeAs('public/pers-files/' . $pers->id, $fileNameSuratTugasWartawan, 'public');
                    $pathSuratTugasWartawan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSuratTugasWartawan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSuratTugasWartawan,
                            'file_path' => $pathSuratTugasWartawan,
                            'file_type' => 'surat_tugas_wartawan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
            } else if ($request->jenis_media == 'Media Elektronik') {
                if ($request->hasFile('akta_pendirian')) {
                    $fileNameAktaPendirian = 'akta_pendirian_' . $pers->unique_id . '.' . $request->file('akta_pendirian')->extension();
                    $request->file('akta_pendirian')->storeAs('public/pers-files/' . $pers->id, $fileNameAktaPendirian, 'public');
                    $pathAktaPendirian = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameAktaPendirian;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $request->nama_perusahaan,
                            'file_name' => $fileNameAktaPendirian,
                            'file_path' => $pathAktaPendirian,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sk_kemenkumham')) {
                    $fileNameSkKemenkumham = 'sk_kemenkumham_' . $pers->unique_id . '.' . $request->file('sk_kemenkumham')->extension();
                    $request->file('sk_kemenkumham')->storeAs('public/pers-files/' . $pers->id, $fileNameSkKemenkumham, 'public');
                    $pathSkKemenkumham = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSkKemenkumham;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSkKemenkumham,
                            'file_path' => $pathSkKemenkumham,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('izin_ipp')) {
                    $fileNameIzinIpp = 'izin_ipp_' . $pers->unique_id . '.' . $request->file('izin_ipp')->extension();
                    $request->file('izin_ipp')->storeAs('public/pers-files/' . $pers->id, $fileNameIzinIpp, 'public');
                    $pathIzinIpp = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameIzinIpp;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Izin IPP ' . $request->nama_perusahaan,
                            'file_name' => $fileNameIzinIpp,
                            'file_path' => $pathIzinIpp,
                            'file_type' => 'izin_ipp',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('izin_isr')) {
                    $fileNameIzinIsr = 'izin_isr_' . $pers->unique_id . '.' . $request->file('izin_isr')->extension();
                    $request->file('izin_isr')->storeAs('public/pers-files/' . $pers->id, $fileNameIzinIsr, 'public');
                    $pathIzinIsr = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameIzinIsr;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Izin ISR ' . $request->nama_perusahaan,
                            'file_name' => $fileNameIzinIsr,
                            'file_path' => $pathIzinIsr,
                            'file_type' => 'izin_isr',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('siup')) {
                    $fileNameSiup = 'siup_' . $pers->unique_id . '.' . $request->file('siup')->extension();
                    $request->file('siup')->storeAs('public/pers-files/' . $pers->id, $fileNameSiup, 'public');
                    $pathSiup = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSiup;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSiup,
                            'file_path' => $pathSiup,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('tdp_penyiaran_60102')) {
                    $fileNameTdpPenyiaran60102 = 'tdp_penyiaran_60102_' . $pers->unique_id . '.' . $request->file('tdp_penyiaran_60102')->extension();
                    $request->file('tdp_penyiaran_60102')->storeAs('public/pers-files/' . $pers->id, $fileNameTdpPenyiaran60102, 'public');
                    $pathTdpPenyiaran60102 = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameTdpPenyiaran60102;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TDP Penyiaran 60102 ' . $request->nama_perusahaan,
                            'file_name' => $fileNameTdpPenyiaran60102,
                            'file_path' => $pathTdpPenyiaran60102,
                            'file_type' => 'tdp_penyiaran_60102',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('spt_terakhir')) {
                    $fileNameSptTerakhir = 'spt_terakhir_' . $pers->unique_id . '.' . $request->file('spt_terakhir')->extension();
                    $request->file('spt_terakhir')->storeAs('public/pers-files/' . $pers->id, $fileNameSptTerakhir, 'public');
                    $pathSptTerakhir = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSptTerakhir;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSptTerakhir,
                            'file_path' => $pathSptTerakhir,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sp_cakupan_wilayah')) {
                    $fileNameSpCakupanWilayah = 'sp_cakupan_wilayah_' . $pers->unique_id . '.' . $request->file('sp_cakupan_wilayah')->extension();
                    $request->file('sp_cakupan_wilayah')->storeAs('public/pers-files/' . $pers->id, $fileNameSpCakupanWilayah, 'public');
                    $pathSpCakupanWilayah = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSpCakupanWilayah;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Cakupan Wilayah ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSpCakupanWilayah,
                            'file_path' => $pathSpCakupanWilayah,
                            'file_type' => 'sp_cakupan_wilayah',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sp_pimpinan')) {
                    $fileNameSpPimpinan = 'sp_pimpinan_' . $pers->unique_id . '.' . $request->file('sp_pimpinan')->extension();
                    $request->file('sp_pimpinan')->storeAs('public/pers-files/' . $pers->id, $fileNameSpPimpinan, 'public');
                    $pathSpPimpinan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSpPimpinan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SP Pimpinan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSpPimpinan,
                            'file_path' => $pathSpPimpinan,
                            'file_type' => 'sp_pimpinan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sk_biro_iklan')) {
                    $fileNameSkBiroIklan = 'sk_biro_iklan_' . $pers->unique_id . '.' . $request->file('sk_biro_iklan')->extension();
                    $request->file('sk_biro_iklan')->storeAs('public/pers-files/' . $pers->id, $fileNameSkBiroIklan, 'public');
                    $pathSkBiroIklan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSkBiroIklan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Biro Iklan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSkBiroIklan,
                            'file_path' => $pathSkBiroIklan,
                            'file_type' => 'sk_biro_iklan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('surat_tugas_wartawan')) {
                    $fileNameSuratTugasWartawan = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $request->file('surat_tugas_wartawan')->extension();
                    $request->file('surat_tugas_wartawan')->storeAs('public/pers-files/' . $pers->id, $fileNameSuratTugasWartawan, 'public');
                    $pathSuratTugasWartawan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSuratTugasWartawan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSuratTugasWartawan,
                            'file_path' => $pathSuratTugasWartawan,
                            'file_type' => 'surat_tugas_wartawan',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }
            } else if ($request->jenis_media == 'Media Siber') {
                if ($request->hasFile('akta_pendirian')) {
                    $fileNameAktaPendirian = 'akta_pendirian_' . $pers->unique_id . '.' . $request->file('akta_pendirian')->extension();
                    $request->file('akta_pendirian')->storeAs('public/pers-files/' . $pers->id, $fileNameAktaPendirian, 'public');
                    $pathAktaPendirian = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameAktaPendirian;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Akta Pendirian ' . $request->nama_perusahaan,
                            'file_name' => $fileNameAktaPendirian,
                            'file_path' => $pathAktaPendirian,
                            'file_type' => 'akta_pendirian',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sk_kemenkumham')) {
                    $fileNameSkKemenkumham = 'sk_kemenkumham_' . $pers->unique_id . '.' . $request->file('sk_kemenkumham')->extension();
                    $request->file('sk_kemenkumham')->storeAs('public/pers-files/' . $pers->id, $fileNameSkKemenkumham, 'public');
                    $pathSkKemenkumham = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSkKemenkumham;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Kemenkumham ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSkKemenkumham,
                            'file_path' => $pathSkKemenkumham,
                            'file_type' => 'sk_kemenkumham',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('siup')) {
                    $fileNameSiup = 'siup_' . $pers->unique_id . '.' . $request->file('siup')->extension();
                    $request->file('siup')->storeAs('public/pers-files/' . $pers->id, $fileNameSiup, 'public');
                    $pathSiup = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSiup;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SIUP ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSiup,
                            'file_path' => $pathSiup,
                            'file_type' => 'siup',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('tdp_penerbitan_63122')) {
                    $fileNameTdpPenerbitan63122 = 'tdp_penerbitan_63122_' . $pers->unique_id . '.' . $request->file('tdp_penerbitan_63122')->extension();
                    $request->file('tdp_penerbitan_63122')->storeAs('public/pers-files/' . $pers->id, $fileNameTdpPenerbitan63122, 'public');
                    $pathTdpPenerbitan63122 = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameTdpPenerbitan63122;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'TDP Penerbitan 63122 ' . $request->nama_perusahaan,
                            'file_name' => $fileNameTdpPenerbitan63122,
                            'file_path' => $pathTdpPenerbitan63122,
                            'file_type' => 'tdp_penerbitan_63122',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('spt_terakhir')) {
                    $fileNameSptTerakhir = 'spt_terakhir_' . $pers->unique_id . '.' . $request->file('spt_terakhir')->extension();
                    $request->file('spt_terakhir')->storeAs('public/pers-files/' . $pers->id, $fileNameSptTerakhir, 'public');
                    $pathSptTerakhir = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSptTerakhir;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SPT Terakhir ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSptTerakhir,
                            'file_path' => $pathSptTerakhir,
                            'file_type' => 'spt_terakhir',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('situ')) {
                    $fileNameSitu = 'situ_' . $pers->unique_id . '.' . $request->file('situ')->extension();
                    $request->file('situ')->storeAs('public/pers-files/' . $pers->id, $fileNameSitu, 'public');
                    $pathSitu = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSitu;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SITU ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSitu,
                            'file_path' => $pathSitu,
                            'file_type' => 'situ',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('sk_domisili')) {
                    $fileNameSkDomisili = 'sk_domisili_' . $pers->unique_id . '.' . $request->file('sk_domisili')->extension();
                    $request->file('sk_domisili')->storeAs('public/pers-files/' . $pers->id, $fileNameSkDomisili, 'public');
                    $pathSkDomisili = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSkDomisili;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'SK Domisili ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSkDomisili,
                            'file_path' => $pathSkDomisili,
                            'file_type' => 'sk_domisili',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                }

                if ($request->hasFile('surat_tugas_wartawan')) {
                    $fileNameSuratTugasWartawan = 'surat_tugas_wartawan_' . $pers->unique_id . '.' . $request->file('surat_tugas_wartawan')->extension();
                    $request->file('surat_tugas_wartawan')->storeAs('public/pers-files/' . $pers->id, $fileNameSuratTugasWartawan, 'public');
                    $pathSuratTugasWartawan = 'storage/public/pers-files/' . $pers->id . '/' . $fileNameSuratTugasWartawan;

                    DB::table('pers_profile_files')
                        ->insertGetId([
                            'pers_profile_id' => $pers->id,
                            'title' => 'Surat Tugas Wartawan ' . $request->nama_perusahaan,
                            'file_name' => $fileNameSuratTugasWartawan,
                            'file_path' => $pathSuratTugasWartawan,
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
            if ($request->cakupan_media == 'Nasional/Regional') {
                $tierPoint += 80;
            }
            if ($request->cakupan_media == 'Regional') {
                $tierPoint += 70;
            }
            if ($request->cakupan_media == 'Kabupaten') {
                $tierPoint += 60;
            }

            // jumlah oplah
            if ($request->jumlah_oplah == '> 100.000') {
                $tierPoint += 90;
            }
            if ($request->jumlah_oplah == '25.001-100.000') {
                $tierPoint += 80;
            }
            if ($request->jumlah_oplah == '10.001-25.000') {
                $tierPoint += 70;
            }
            if ($request->jumlah_oplah == '1.001-10.000') {
                $tierPoint += 60;
            }
            if ($request->jumlah_oplah == '< 1.000') {
                $tierPoint += 20;
            }

            // sebaran oplah
            if ($request->sebaran_oplah == '11-16 Kecamatan') {
                $tierPoint += 80;
            }
            if ($request->sebaran_oplah == '6-10 Kecamatan') {
                $tierPoint += 70;
            }
            if ($request->sebaran_oplah == '< 5 Kecamatan') {
                $tierPoint += 60;
            }

            // status wartawan
            if ($request->status_wartawan == 'Ada Khusus') {
                $tierPoint += 60;
            }
            if ($request->status_wartawan == 'Ada Merangkap Kabupaten Lain') {
                $tierPoint += 40;
            }
            if ($request->status_wartawan == 'Tidak Ada') {
                $tierPoint += 20;
            }

            // kompetensi wartawan
            if ($request->kompetensi_wartawan == 'Memiliki Sertifikat Kompetensi') {
                $tierPoint += 80;
            }
            if ($request->kompetensi_wartawan == 'Tidak Memiliki') {
                $tierPoint += 30;
            }

            // status dewan pers
            if ($request->status_dewan_pers == 'Terdaftar') {
                $tierPoint += 80;
            }
            if ($request->status_dewan_pers == 'Tidak Terdaftar') {
                $tierPoint += 30;
            }

            // kantor
            if ($request->kantor == 'Ada') {
                $tierPoint += 20;
            }
            if ($request->kantor == 'Tidak Ada') {
                $tierPoint += 10;
            }

            // frekuensi terbitan
            if ($request->frekuensi_terbitan == '1 kali sehari') {
                $tierPoint += 80;
            }
            if ($request->frekuensi_terbitan == '1 kali seminggu') {
                $tierPoint += 70;
            }
            if ($request->frekuensi_terbitan == '2 mingguan') {
                $tierPoint += 60;
            }
            if ($request->frekuensi_terbitan == '1 kali sebulan') {
                $tierPoint += 40;
            }

            // terbitan 3 edisi terakhir
            if ($request->terbitan_3_edisi_terakhir == 'Ada') {
                $tierPoint += 40;
            }
            if ($request->terbitan_3_edisi_terakhir == 'Tidak Ada') {
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
                    'tier' => $tier
                ]);


            $pers = DB::table('pers_profile')
                ->where('user_id', $user->id)
                ->first();

            $return = [
                'id' => $pers->id,
                'jenis_media' => $pers->jenis_media,
                'nama_media' => $pers->nama_media,
                'nama_perusahaan' => $pers->nama_perusahaan,
                'alamat_media' => $pers->alamat_media,
                'whatsapp' => $pers->whatsapp,
                'email' => $pers->email,
                'no_npwp' => $pers->no_npwp,
                'no_ref_bank' => $pers->no_ref_bank,
                'no_giro_perusahaan' => $pers->no_giro_perusahaan,
                'website' => $pers->website,
                'jabatan' => $pers->jabatan,
                'profil_perusahaan' => $pers->profil_perusahaan,
                'cakupan_media' => $pers->cakupan_media,
                'jumlah_oplah' => $pers->jumlah_oplah,
                'sebaran_oplah' => $pers->sebaran_oplah,
                'status_wartawan' => $pers->status_wartawan,
                'kompetensi_wartawan' => $pers->kompetensi_wartawan,
                'status_dewan_pers' => $pers->status_dewan_pers,
                'kantor' => $pers->kantor,
                'frekuensi_terbitan' => $pers->frekuensi_terbitan,
                'terbitan_3_edisi_terakhir' => $pers->terbitan_3_edisi_terakhir,

                'file_jumlah_oplah' => $pers->file_jumlah_oplah ? asset($pers->file_jumlah_oplah) : null,
                'file_status_wartawan' => $pers->file_status_wartawan ? asset($pers->file_status_wartawan) : null,
                'file_kompetensi_wartawan' => $pers->file_kompetensi_wartawan ? asset($pers->file_kompetensi_wartawan) : null,
                'file_status_dewan_pers' => $pers->file_status_dewan_pers ? asset($pers->file_status_dewan_pers) : null,
                'file_terbitan_3_edisi_terakhir' => $pers->file_terbitan_3_edisi_terakhir ? asset($pers->file_terbitan_3_edisi_terakhir) : null,

                'tier_point' => $pers->tier_point,
                'tier' => $pers->tier,
                'verified_status' => $pers->verified_status,
                'verification_deadline' => $pers->verification_deadline,
                'verified_at' => $pers->verified_at,
                'created_at' => $pers->created_at,
                'updated_at' => $pers->updated_at,

            ];

            $arrFiles = [];
            $typeFiles = DB::table('pers_profile_files')
                ->where('pers_profile_id', $pers->id)
                ->pluck('file_type');
            foreach ($typeFiles as $type) {
                $file = DB::table('pers_profile_files')
                    ->where('pers_profile_id', $pers->id)
                    ->where('file_type', $type)
                    ->latest('created_at')
                    ->first();

                if ($file) {
                    $arrFiles[] = [
                        'id' => $file->id,
                        'title' => $file->title,
                        'file_type' => $type,
                        'file_name' => $file->file_name,
                        'file_path' => asset($file->file_path),
                        'created_at' => $file->created_at,
                        'updated_at' => $file->updated_at,
                    ];
                }
            }
            $return['files'] = $arrFiles;

            DB::commit();
            return $this->successResponse($return, 'Berkas Anda berhasil diunggah. Silahkan tunggu verifikasi dari admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
