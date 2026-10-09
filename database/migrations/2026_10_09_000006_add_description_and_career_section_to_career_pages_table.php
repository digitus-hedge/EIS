<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the banner description and the career section title + description to career_pages. */
    public function up(): void
    {
        Schema::table('career_pages', function (Blueprint $table) {
            $table->text('banner_description')->nullable()->after('banner_title');
            $table->string('career_title')->nullable()->after('banner_video');
            $table->text('career_description')->nullable()->after('career_title');
        });
    }

    public function down(): void
    {
        Schema::table('career_pages', function (Blueprint $table) {
            $table->dropColumn(['banner_description', 'career_title', 'career_description']);
        });
    }
};
