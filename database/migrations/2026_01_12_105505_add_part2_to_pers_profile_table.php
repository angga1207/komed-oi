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
            $table->boolean('follower_required')->default(false)->after('website');
            $table->integer('latest_viewer')->default(0)->after('follower_required');
            $table->json('social_media')->nullable()->after('latest_viewer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pers_profile', function (Blueprint $table) {
            $table->dropColumn(['follower_required', 'latest_viewer', 'media_social']);
        });
    }
};
