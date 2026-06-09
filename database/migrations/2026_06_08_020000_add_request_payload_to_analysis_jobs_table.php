<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analysis_jobs', function (Blueprint $table) {
            $table->json('request_payload')->nullable()->after('results_data');
        });
    }

    public function down(): void
    {
        Schema::table('analysis_jobs', function (Blueprint $table) {
            $table->dropColumn('request_payload');
        });
    }
};
