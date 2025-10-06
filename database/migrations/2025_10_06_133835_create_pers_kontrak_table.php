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
        Schema::create('pers_kontrak', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('pers_profile_id')->constrained('pers_profile')->onDelete('cascade');
            $table->string('jenis_kontrak');
            $table->year('tahun');
            $table->text('nomor_kontrak')->nullable();
            $table->double('nilai_kontrak')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pers_kontrak');
    }
};
