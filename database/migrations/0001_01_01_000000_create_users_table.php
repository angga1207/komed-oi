<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->longText('email')->unique();
            $table->string('photo')->default('default.png');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('google_id')->nullable();
            $table->enum('status', ['pending', 'active', 'suspend', 'banned'])->default('pending')->nullable();
            $table->string('role_id');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pers_profile', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nik')->unique();
            $table->text('nama_perusahaan')->nullable();
            $table->text('nama_media')->nullable();
            $table->text('alias')->nullable();
            $table->text('jenis_media')->nullable();
            $table->longText('alamat_media')->nullable();
            $table->longText('logo_media')->nullable();

            $table->longText('whatsapp')->nullable();
            $table->longText('email')->nullable();
            $table->longText('no_npwp')->nullable();
            $table->longText('no_ref_bank')->nullable();
            $table->longText('no_giro_perusahaan')->nullable();
            $table->longText('website')->nullable();
            $table->text('file_ktp')->nullable()->after('whatsapp');
            $table->longText('profil_perusahaan')->nullable();

            $table->timestamp('verification_deadline')->nullable();


            $table->enum('verified_status', ['pending', 'verified', 'suspend', 'banned'])->default('pending')->nullable();
            $table->enum('tier', ['0', '1', '2', '3'])->nullable();
            $table->double('tier_point')->default(0);

            $table->text('cakupan_media')->nullable();
            $table->text('jumlah_oplah')->nullable();
            $table->text('sebaran_oplah')->nullable();
            $table->text('status_wartawan')->nullable();
            $table->text('kompetensi_wartawan')->nullable();
            $table->text('status_dewan_pers')->nullable();
            $table->text('kantor')->nullable();
            $table->text('frekuensi_terbitan')->nullable();
            $table->text('terbitan_3_edisi_terakhir')->nullable();

            $table->text('file_jumlah_oplah')->nullable();
            $table->text('file_status_wartawan')->nullable();
            $table->text('file_kompetensi_wartawan')->nullable();
            $table->text('file_status_dewan_pers')->nullable();
            $table->text('file_terbitan_3_edisi_terakhir')->nullable();

            $table->longText('extend_verification_message')->nullable()->after('verification_deadline');
            $table->softDeletes();
            $table->timestamps();
        });


        // $detailMediaCetak = [
        //     'cakupan_media' => null,
        //     'jumlah_oplah' => null,
        //     'sebaran_oplah' => null,
        //     'status_wartawan' => null,
        //     'kompetensi_wartawan' => null,
        //     'status_dewan_pers' => null,
        //     'kantor' => null,
        //     'frekuensi_terbitan' => null,
        //     'terbitan_3_edisi_terakhir' => null,

        //     'file_jumlah_oplah' => null,
        //     'file_status_wartawan' => null,
        //     'file_kompetensi_wartawan' => null,
        //     'file_status_dewan_pers' => null,
        //     'file_terbitan_3_edisi_terakhir' => null,
        // ];


        // => FOR PERS PROFILE FILES
        // $berkas = [
        //     'akta_pendirian' => null,
        //     'sk_kemenkumham' => null,
        //     'izin_ipp' => null,
        //     'izin_isr' => null,
        //     'siup' => null,
        //     'tdp_penyiaran_60102' => null,
        //     'tdp_penerbitan_63122' => null,
        //     'tdp_penerbitan_58130' => null,
        //     'npwp' => null,
        //     'spt_terakhir' => null,
        //     'sp_cakupan_wilayah' => null,
        //     'sp_pimpinan' => null,
        //     'sk_biro_iklan' => null,
        //     'situ' => null,
        //     'sk_domisili' => null,
        //     'surat_tugas_wartawan' => null,
        // ];

        Schema::create('pers_profile_files', function (Blueprint $table) {
            $table->id();
            $table->integer('pers_profile_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();
        });

        Schema::create('laporan_pers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('media_pers_id')->nullable();
            $table->string('judul')->nullable();
            $table->string('slug')->nullable();
            $table->dateTime('tanggal_publikasi')->nullable();
            $table->string('link')->nullable();
            $table->json('lampiran')->nullable();
            $table->longText('catatan_admin')->nullable();
            $table->longText('catatan_pelapor')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected', 'paid', 'unpaid'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('pers_profile');
        Schema::dropIfExists('pers_profile_files');
        Schema::dropIfExists('laporan_pers');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
