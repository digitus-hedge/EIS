<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->longText('description')->nullable(); // longText since it stores HTML from the editor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_abouts');
    }
};