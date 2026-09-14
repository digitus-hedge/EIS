<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->string('who_we_are_meta_title', 255)->nullable()->after('who_we_are_desc');
            $table->text('who_we_are_meta_description')->nullable()->after('who_we_are_meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn(['who_we_are_meta_title', 'who_we_are_meta_description']);
        });
    }
};