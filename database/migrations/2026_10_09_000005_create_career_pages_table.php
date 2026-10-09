<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** One row only: the banner and SEO details of the website's Career page. */
    public function up(): void
    {
        Schema::create('career_pages', function (Blueprint $table) {
            $table->id();
            $table->string('banner_title')->nullable();
            $table->string('banner')->nullable();          // banner image, path on the public disk
            $table->string('banner_video')->nullable();    // banner video, path on the public disk
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_pages');
    }
};
