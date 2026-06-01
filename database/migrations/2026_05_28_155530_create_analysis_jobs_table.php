<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analysis_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('job_id')->unique();
            $table->string('title');
            $table->string('alignment_index');
            $table->string('reads_type');
            $table->string('reads_file');
            $table->string('reads_file_type')->default('server'); // 'server' or 'uploaded'
            $table->string('reference_sequence');
            $table->string('annotation_db');
            $table->json('advanced_params')->nullable();
            $table->string('status')->default('SUBMITTED'); // SUBMITTED, RUNNING, FINISHED, CANCELED
            $table->string('current_step')->nullable(); // alignment, sorting, snp_calling, snp_filtering, snp_annotation, completed
            $table->dateTime('submit_date');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->string('output_path')->nullable();
            $table->longText('logs')->nullable();
            $table->json('results_data')->nullable(); // JSON for variant list, percentages, stats
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_jobs');
    }
};
