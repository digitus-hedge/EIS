<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('why_choose_us', function (Blueprint $table) {
            $table->dropColumn([
                'mission_title', 'mission_description', 'mission_image',
                'vision_title', 'vision_description', 'vision_image',
                'values_title', 'values_description', 'values_image',
                'commitment_title', 'commitment_description',
            ]);
            $table->json('items')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('why_choose_us', function (Blueprint $table) {
            $table->dropColumn('items');
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
        });
    }
};