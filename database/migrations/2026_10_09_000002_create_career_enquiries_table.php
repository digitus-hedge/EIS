<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            // the career applied for: the link is cleared if that career is deleted, the title is kept
            $table->foreignId('career_id')->nullable()->constrained('careers')->nullOnDelete();
            $table->string('apply_for');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_enquiries');
    }
};
