<?php

use App\Http\Controllers\Api\PipelineJobController;
use Illuminate\Support\Facades\Route;

Route::prefix('pipeline')->group(function () {
    Route::post('/jobs', [PipelineJobController::class, 'store'])->name('api.pipeline.jobs.store');
    Route::get('/jobs/{job}', [PipelineJobController::class, 'show'])->name('api.pipeline.jobs.show');
});
