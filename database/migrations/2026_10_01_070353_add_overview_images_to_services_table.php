<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('process_images')->nullable()->after('process_image');
        });

        // Move each existing single process image into the new list
        DB::table('services')->whereNotNull('process_image')->orderBy('id')->each(function ($row) {
            DB::table('services')->where('id', $row->id)
                ->update(['process_images' => json_encode([$row->process_image])]);
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('process_images');
        });
    }
};