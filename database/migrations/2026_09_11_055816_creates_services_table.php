<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // ===== Banner section =====
            $table->string('banner_title');
            $table->string('slug')->unique();
            $table->text('banner_description')->nullable();
            $table->string('banner_image')->nullable();

            // ===== Overview section =====
            $table->string('overview_title')->nullable();
            $table->text('overview_description')->nullable();
            $table->string('overview_image')->nullable();

            // ===== Process section =====
            // JSON array of rows: [{ "description": "...", "video": "services/videos/xxx.mp4" }, ...]
            // Unlimited rows, admin can add more.
            $table->json('process')->nullable();

            // ===== Features section =====
            $table->string('features_heading')->nullable();
            // JSON array of up to 4 rows: [{ "icon": "services/icons/xxx.webp", "title": "...", "description": "..." }, ...]
            $table->json('features')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};