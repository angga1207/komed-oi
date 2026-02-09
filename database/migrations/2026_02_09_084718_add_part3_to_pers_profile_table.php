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
        Schema::table('pers_profile', function (Blueprint $table) {
            $table->string('nama_pemilik_rekening')->nullable()->after('social_media');
            $table->string('nama_wartawan')->nullable()->after('nama_pemilik_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pers_profile', function (Blueprint $table) {
            $table->dropColumn(['nama_pemilik_rekening', 'nama_wartawan']);
        });
    }
};
