<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('why_choose_us', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();

            $table->string('mission_title')->nullable();
            $table->text('mission_description')->nullable();
            $table->string('mission_image')->nullable();

            $table->string('vision_title')->nullable();
            $table->text('vision_description')->nullable();
            $table->string('vision_image')->nullable();

            $table->string('values_title')->nullable();
            $table->text('values_description')->nullable();
            $table->string('values_image')->nullable();

            $table->string('commitment_title')->nullable();
            $table->text('commitment_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('why_choose_us');
    }
};
