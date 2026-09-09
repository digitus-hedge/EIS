<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regional_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_location_id')
                ->constrained('regional_locations')
                ->cascadeOnDelete();
            $table->string('title');            // e.g. "Erbil, Iraq" (office card heading)
            $table->text('description')->nullable(); // e.g. "Headquarters & inspection operations"
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_offices');
    }
};