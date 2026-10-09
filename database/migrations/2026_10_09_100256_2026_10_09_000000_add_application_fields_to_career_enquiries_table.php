<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the extra fields of the website's "Submit CV" form to career_enquiries.
 * Each column is only added if it is not there yet, so this is safe to run
 * even if some of them (for example "cv") already exist.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_enquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('career_enquiries', 'nationality')) {
                $table->string('nationality', 100)->nullable();
            }
            if (! Schema::hasColumn('career_enquiries', 'location')) {
                $table->string('location', 150)->nullable();
            }
            if (! Schema::hasColumn('career_enquiries', 'message')) {
                $table->text('message')->nullable();
            }
            if (! Schema::hasColumn('career_enquiries', 'cv')) {
                $table->string('cv')->nullable();
            }
        });
    }

    public function down(): void
    {
        // "cv" is left in place because it may have existed before this migration.
        foreach (['nationality', 'location', 'message'] as $column) {
            if (Schema::hasColumn('career_enquiries', $column)) {
                Schema::table('career_enquiries', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};