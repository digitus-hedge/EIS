<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();   // static image shown on the card / hero
            $table->string('video')->nullable();        // uploaded mp4 path
            $table->boolean('is_main')->default(false); // true = the big hero video, false = carousel clip
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_videos');
    }
};