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
        Schema::create('agendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('jadwalin_bae_id')->nullable();
            $table->json('data');
            $table->dateTime('tanggal_pelaksanaan');
            $table->dateTime('tanggal_pelaksanaan_akhir');
            $table->time('waktu_pelaksanaan');
            $table->text('leading_sector')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_code')->unique();
            $table->unsignedBigInteger('media_id')->index();
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->bigInteger('jadwalin_bae_id')->nullable();
            $table->dateTime('tanggal_pelaksanaan');
            $table->dateTime('tanggal_pelaksanaan_akhir');
            $table->time('waktu_pelaksanaan');
            $table->text('leading_sector')->nullable();
            $table->enum('status', ['sent', 'accepted', 'rejected', 'review', 'verified', 'unverified', 'done'])->default('sent');
            $table->softDeletes();
            $table->timestamps();

            // media_id to pers_profile table
            $table->foreign('media_id')->references('id')->on('pers_profile');
        });

        Schema::create('log_order_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('media_id')->index();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->bigInteger('jadwalin_bae_id')->nullable();
            $table->string('status');
            $table->longText('note')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('pers_profile');
        });

        Schema::create('order_evidences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('media_id')->index();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image', 'video', 'document', 'link', 'other']);
            $table->text('url');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('pers_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('log_order_status');
        Schema::dropIfExists('order_evidences');
    }
};
