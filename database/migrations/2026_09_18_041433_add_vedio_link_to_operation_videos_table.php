<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operation_videos', function (Blueprint $table) {
            $table->string('vedio_link')->nullable()->after('video');
            $table->string('video')->nullable()->change(); // video file is no longer strictly required
        });
    }

    public function down(): void
    {
        Schema::table('operation_videos', function (Blueprint $table) {
            $table->dropColumn('vedio_link');
        });
    }
};