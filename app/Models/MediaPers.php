<?php

namespace App\Models;

use App\Searchable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaPers extends Model
{
    use HasFactory, Searchable;
    protected $guarded = [];
    protected $table = 'pers_profile';

    protected $searchable = [
        'unique_id',
        'nama_perusahaan',
        'nama_media',
        'alias',
        'jenis_media',
        'alamat_media',
        'whatsapp',
        'email',
        'User.fullname',
    ];

    // boot method to create a unique ID when creating a new record
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->unique_id = $model->generateUniqueID();
        });
    }

    public static function generateUniqueIDStatic($jenis_media)
    {
        if ($jenis_media == 'Media Cetak') {
            $jenisMedia = '01';
        } elseif ($jenis_media == 'Media Elektronik') {
            $jenisMedia = '02';
        } elseif ($jenis_media == 'Media Siber') {
            $jenisMedia = '03';
        } elseif ($jenis_media == 'Media Sosial') {
            $jenisMedia = '04';
        } elseif ($jenis_media == 'Multimedia') {
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
        // if (MediaPers::checkUniqueIDExistsStatic($unique_id) == false) {
        //     return MediaPers::generateUniqueIDStatic($jenis_media);
        // }
        return $unique_id;
    }

    private function generateUniqueID()
    {
        if ($this->jenis_media == 'Media Cetak') {
            $jenisMedia = '01';
        } elseif ($this->jenis_media == 'Media Elektronik') {
            $jenisMedia = '02';
        } elseif ($this->jenis_media == 'Media Siber') {
            $jenisMedia = '03';
        } elseif ($this->jenis_media == 'Media Sosial') {
            $jenisMedia = '04';
        } elseif ($this->jenis_media == 'Multimedia') {
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

        $unique_id = $str = $format . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        if ($this->checkUniqueIDExists($unique_id) == false) {
            $this->generateUniqueID();
        }
        return $unique_id;
    }

    private function checkUniqueIDExists($unique_id)
    {
        $pers = DB::table('pers_profile')->where('unique_id', $unique_id)->first();
        if ($pers) {
            return false;
        } else {
            return true;
        }
    }

    function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function RegisterFiles()
    {
        return $this->hasMany(MediaPersFiles::class, 'pers_profile_id', 'id');
    }

    function isBasicFilesComplete()
    {
        $allStatus = false;
        $status_wartawan = false;
        if (($this->status_wartawan == 'Ada Khusus' || $this->status_wartawan == 'Ada Merangkap Kabupaten Lain') && $this->file_status_wartawan) {
            $status_wartawan = true;
        } else if ($this->status_wartawan == 'Tidak Ada') {
            $status_wartawan = true;
        }

        $status_kompetensi_wartawan = false;
        if ($this->kompetensi_wartawan == 'Memiliki Sertifikat Kompetensi' && $this->file_kompetensi_wartawan) {
            $status_kompetensi_wartawan = true;
        } else if ($this->kompetensi_wartawan != 'Memiliki Sertifikat Kompetensi') {
            $status_kompetensi_wartawan = true;
        }

        $status_dewan_pers = false;
        if ($this->status_dewan_pers == 'Terdaftar' && $this->file_status_dewan_pers) {
            $status_dewan_pers = true;
        } else if ($this->status_dewan_pers != 'Terdaftar') {
            $status_dewan_pers = true;
        }

        $status_terbitan_3_edisi_terakhir = false;
        if ($this->terbitan_3_edisi_terakhir == 'Ada' && $this->file_terbitan_3_edisi_terakhir) {
            $status_terbitan_3_edisi_terakhir = true;
        } else if ($this->terbitan_3_edisi_terakhir != 'Ada') {
            $status_terbitan_3_edisi_terakhir = true;
        }

        if ($status_wartawan && $status_kompetensi_wartawan && $status_dewan_pers && $status_terbitan_3_edisi_terakhir) {
            $allStatus = true;
        }

        if ($allStatus) {
            return true;
        } else {
            return false;
        }
    }

    function isFilesComplete()
    {
        $filesStatus = false;
        if ($this->jenis_media == 'Media Cetak') {
            $arrKeys = [
                'akta_pendirian',
                'sk_kemenkumham',
                'siup',
                'tdp_penerbitan_58130',
                'spt_terakhir',
                'sp_cakupan_wilayah',
                'sp_pimpinan',
                'surat_tugas_wartawan',
            ];

            // check on table pers_profile_files
            $countFiles = DB::table('pers_profile_files')
                ->where('pers_profile_id', $this->id)
                ->whereIn('file_type', $arrKeys)
                ->count();

            if ($countFiles == count($arrKeys)) {
                $filesStatus = true;
            }
        } else if ($this->jenis_media == 'Media Elektronik') {
            $arrKeys = [
                'akta_pendirian',
                'sk_kemenkumham',
                'izin_ipp',
                'izin_isr',
                'siup',
                'tdp_penyiaran_60102',
                'spt_terakhir',
                'sp_cakupan_wilayah',
                'sp_pimpinan',
                'sk_biro_iklan',
                'surat_tugas_wartawan',
            ];

            // check on table pers_profile_files
            $countFiles = DB::table('pers_profile_files')
                ->where('pers_profile_id', $this->id)
                ->whereIn('file_type', $arrKeys)
                ->count();

            if ($countFiles == count($arrKeys)) {
                $filesStatus = true;
            }
        } else if ($this->jenis_media == 'Media Siber') {
            $arrKeys = [
                'akta_pendirian',
                'sk_kemenkumham',
                'siup',
                'tdp_penerbitan_63122',
                'spt_terakhir',
                'situ',
                'sk_domisili',
                'surat_tugas_wartawan',
            ];

            // check on table pers_profile_files
            $countFiles = DB::table('pers_profile_files')
                ->where('pers_profile_id', $this->id)
                ->whereIn('file_type', $arrKeys)
                ->count();

            if ($countFiles == count($arrKeys)) {
                $filesStatus = true;
            }
        }

        if ($filesStatus) {
            return true;
        } else {
            return false;
        }
    }

    function Orders()
    {
        return $this->hasMany(Order::class, 'media_id', 'id');
    }

    function OrdersFilter($type = null, $query = null)
    {
        if ($type == 'bulan_ini') {
            return $this->hasMany(Order::class, 'media_id', 'id')
                ->whereMonth('tanggal_pelaksanaan', now())
                ->orderBy('tanggal_pelaksanaan')
                ->get();
        } else if ($type == 'mendatang') {
            return $this->hasMany(Order::class, 'media_id', 'id')
                ->whereDate('tanggal_pelaksanaan', '>', now())
                ->orderBy('tanggal_pelaksanaan')
                ->get();
        } else if ($type == 'hari_ini') {
            return $this->hasMany(Order::class, 'media_id', 'id')
                ->whereDate('tanggal_pelaksanaan', now())
                ->orderBy('tanggal_pelaksanaan')
                ->get();
        } else {
            return $this->hasMany(Order::class, 'media_id', 'id');
        }
    }

    function KontrakInduk($tahun = null)
    {
        if (!$tahun) {
            $tahun = Carbon::now()->year;
        }
        return $this->hasOne(MediaKontrak::class, 'pers_profile_id', 'id')
            ->where('tahun', $tahun)
            ->where('jenis_kontrak', 'induk');
    }

    function KontrakAPBDP($tahun = null)
    {
        if (!$tahun) {
            $tahun = Carbon::now()->year;
        }
        return $this->hasOne(MediaKontrak::class, 'pers_profile_id', 'id')
            ->where('tahun', $tahun)
            ->where('jenis_kontrak', 'apbdp');
    }
}
