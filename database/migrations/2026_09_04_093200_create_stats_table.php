<?php
// database/migrations/xxxx_xx_xx_create_stats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->string('value');        // e.g. "100K", "15+", "18%"
            $table->string('label');        // e.g. "LICENSES"
            $table->string('description');  // e.g. "Held & managed"
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(1); // active/inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};