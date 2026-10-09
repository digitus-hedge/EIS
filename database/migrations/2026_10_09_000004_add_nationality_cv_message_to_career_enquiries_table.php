<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_enquiries', function (Blueprint $table) {
            // nullable so the enquiries already in the table stay valid
            $table->string('nationality')->nullable()->after('phone');
            $table->string('cv')->nullable()->after('apply_for');      // path of the uploaded CV file
            $table->text('message')->nullable()->after('cv');
        });
    }

    public function down(): void
    {
        Schema::table('career_enquiries', function (Blueprint $table) {
            $table->dropColumn(['nationality', 'cv', 'message']);
        });
    }
};
