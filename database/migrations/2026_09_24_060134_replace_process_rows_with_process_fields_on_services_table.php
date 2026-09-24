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
            $table->string('process_title')->nullable()->after('overview_image');
            $table->text('process_description')->nullable()->after('process_title');
            $table->string('process_image')->nullable()->after('process_description');
        });

        // Carry over existing data: first old process row -> new fields
        DB::table('services')->select('id', 'process')->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $process = is_string($row->process) ? json_decode($row->process, true) : null;
                    $first = is_array($process) ? ($process[0] ?? null) : null;
                    if (!is_array($first)) continue;

                    DB::table('services')->where('id', $row->id)->update([
                        'process_description' => $first['description'] ?? null,
                        'process_image'       => $first['thumbnail'] ?? null,
                    ]);
                }
            });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('process');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('process')->nullable()->after('overview_image');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['process_title', 'process_description', 'process_image']);
        });
    }
};