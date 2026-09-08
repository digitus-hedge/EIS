<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->text('process_description')->nullable()->after('description');
            $table->json('technical_scope')->nullable()->after('process_description');
            $table->json('specifications')->nullable()->after('technical_scope');
            $table->json('gallery')->nullable()->after('image');
            $table->dropColumn('content');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['slug', 'process_description', 'technical_scope', 'specifications', 'gallery']);
            $table->longText('content')->nullable();
        });
    }
};
