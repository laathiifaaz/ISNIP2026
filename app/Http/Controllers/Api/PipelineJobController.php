<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalysisJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PipelineJobController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->json()->all() ?: $request->all();
        $userId = $payload['user_id'] ?? User::query()->value('id');

        if (!$userId || !User::whereKey($userId)->exists()) {
            throw ValidationException::withMessages([
                'user_id' => 'A valid user_id is required to create a pipeline job.',
            ]);
        }

        $maxJob = AnalysisJob::orderBy('id', 'desc')->first();
        $nextNum = $maxJob ? ($maxJob->id + 88401) : 88401;
        $jobId = 'JOB-' . $nextNum;

        $advancedParams = [
            'sensitivity' => data_get($payload, 'advanced_params.sensitivity', data_get($payload, 'sensitivity', 'standard')),
            'min_depth' => data_get($payload, 'advanced_params.min_depth', data_get($payload, 'min_depth', 10)),
            'threads' => data_get($payload, 'advanced_params.threads', data_get($payload, 'threads', 8)),
        ];

        $job = AnalysisJob::create([
            'user_id' => $userId,
            'job_id' => $jobId,
            'title' => $payload['title'] ?? 'REST API Pipeline Job',
            'alignment_index' => $payload['alignment_index'] ?? 'bwa_mem2',
            'reads_type' => $payload['reads_type'] ?? 'paired-end',
            'reads_file' => $payload['reads_file'] ?? data_get($payload, 'input.reads_file', 'pending_request_body.fastq'),
            'reads_file_type' => $payload['reads_file_type'] ?? 'api',
            'reference_sequence' => $payload['reference_sequence'] ?? data_get($payload, 'input.reference_sequence', 'hg38_ref.fa'),
            'annotation_db' => $payload['annotation_db'] ?? data_get($payload, 'input.annotation_db', 'ClinVar'),
            'advanced_params' => $advancedParams,
            'request_payload' => $payload,
            'status' => 'SUBMITTED',
            'current_step' => 'alignment',
            'submit_date' => Carbon::now(),
            'logs' => '[INFO] ' . Carbon::now()->toDateTimeString() . ' - REST API pipeline job submitted.',
        ]);

        return response()->json([
            'message' => 'Pipeline job submitted.',
            'data' => $this->jobResource($job),
        ], 201);
    }

    public function show(AnalysisJob $job): JsonResponse
    {
        $job->checkSimulationStatus();
        $job->refresh();

        return response()->json([
            'data' => $this->jobResource($job),
        ]);
    }

    private function jobResource(AnalysisJob $job): array
    {
        return [
            'id' => $job->id,
            'job_id' => $job->job_id,
            'title' => $job->title,
            'status' => $job->status,
            'current_step' => $job->current_step,
            'submit_date' => optional($job->submit_date)->toISOString(),
            'start_date' => optional($job->start_date)->toISOString(),
            'finish_date' => optional($job->finish_date)->toISOString(),
            'input' => [
                'alignment_index' => $job->alignment_index,
                'reads_type' => $job->reads_type,
                'reads_file' => $job->reads_file,
                'reads_file_type' => $job->reads_file_type,
                'reference_sequence' => $job->reference_sequence,
                'annotation_db' => $job->annotation_db,
                'advanced_params' => $job->advanced_params,
            ],
            'output_path' => $job->output_path,
            'results_data' => $job->results_data,
            'logs' => $job->logs,
            'links' => [
                'status' => route('api.pipeline.jobs.show', $job),
                'web_details' => route('jobs.show', $job),
            ],
        ];
    }
}
