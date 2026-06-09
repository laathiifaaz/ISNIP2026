<?php

namespace App\Http\Controllers;

use App\Models\AnalysisJob;
use App\Models\UserUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $successfulJobs = AnalysisJob::where('status', 'FINISHED')->get();
        $averageRuntime = 0;

        if ($successfulJobs->count() > 0) {
            $totalRuntime = $successfulJobs->sum(function($job) {
                return Carbon::parse($job->submit_date)->diffInMinutes(Carbon::parse($job->updated_at));
            });
            $averageRuntime = round($totalRuntime / $successfulJobs->count(), 0);
        } else {
            $averageRuntime = 42; // Default (fallback)
        }

        // Perbandingan runtime (simulasi atau statis -13m dari minggu lalu)
        $runtimeDifference = -13;

        // Pengecekan: Apakah user sudah login?
        if (Auth::check()) {
            $user = Auth::user();

            // Jalankan simulasi otomatis hanya untuk job milik user asli
            $activeJobs = AnalysisJob::where('user_id', $user->id)
                ->whereIn('status', ['SUBMITTED', 'RUNNING'])
                ->get();
            foreach ($activeJobs as $job) {
                $job->checkSimulationStatus();
            }

            // Hitung metrics asli milik user 
            $totalAnalyses = AnalysisJob::where('user_id', $user->id)->count();
            $currentlyRunning = AnalysisJob::where('user_id', $user->id)
                ->whereIn('status', ['SUBMITTED', 'RUNNING'])
                ->count();

            $storagePath = '/Users/lathiifa/Documents/Capstone/isnip_data';
            $totalSizeBytes = 0;

            if (file_exists($storagePath)) {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath)) as $file) {
                    if ($file->isFile()) {
                        $totalSizeBytes += $file->getSize();
                    }
                }
            }

            if ($totalSizeBytes >= 1073741824) { 
                $storageUsed = number_format($totalSizeBytes / (1024 * 1024 * 1024), 2) . " GB";
            } else {
                $storageUsed = number_format($totalSizeBytes / (1024 * 1024), 2) . " MB";
            }

            $maxQuotaBytes = 10 * 1024 * 1024 * 1024; // Batas kuota simulasi 10GB
            $storagePercentage = round(($totalSizeBytes / $maxQuotaBytes) * 100, 0);
            if ($storagePercentage > 100) $storagePercentage = 100;

            // Query filter job asli 
            $query = AnalysisJob::where('user_id', $user->id);
            $uploadedFiles = UserUpload::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            // === JIKA USER ADALAH GUEST (BELUM LOGIN) ===
            $totalAnalyses = 3;
            $currentlyRunning = 0;
            $storageUsed = "0.15 GB";
            $storagePercentage = 2;

            // Filter data demo dari database (jika ada), atau gunakan query kosong sementara
            $query = AnalysisJob::where('user_id', 0); // Set data demo dengan user_id = 0
            $uploadedFiles = collect(); 
        }

        // Filter pencarian dan tab (berlaku untuk user login maupun guest)
        $statusTab = $request->query('tab', 'all');
        if ($statusTab !== 'all' && Auth::check()) {
            $query->where('status', strtoupper($statusTab));
        }

        if ($request->has('search') && Auth::check()) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('job_id', 'like', '%' . $search . '%');
            });
        }

        $jobs = $query->orderBy('submit_date', 'desc')->paginate(10);

        // Jika Guest dan belum punya data demo di DB, file dibuat manual di view.
        // Bagian pembacaan folder server sequence & reads tetap diizinkan agar Guest bisa melihat pilihan opsi dropdown
        $serverGenomes = ['hg38_ref.fa', 'grch38.fasta', 'sars_cov2.fasta'];
        $serverReads = ['human_sample_R1.fastq.gz', 'human_sample_R2.fastq.gz', 'sars_cov2_sample.fastq'];

        return view('jobs.index', compact(
            'jobs', 'totalAnalyses', 'currentlyRunning', 'storageUsed', 'storagePercentage',
            'averageRuntime', 'runtimeDifference', 'statusTab', 'serverGenomes', 'serverReads', 'uploadedFiles'
        ));
    }

    public function show($id)
    {
        // Cari job tanpa membatasi user_id agar Guest bisa melihat detail Job Demo (User ID 0)
        $job = AnalysisJob::findOrFail($id);
        
        $job->checkSimulationStatus();

        return view('jobs.details', compact('job'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'alignment_index' => ['required', 'string'],
            'reads_type' => ['required', 'string'],
            'reference_sequence' => ['required', 'string'],
            'annotation_db' => ['required', 'string'],
            'reads_source' => ['required', 'string'], // 'server', 'previous', or 'upload'
            'server_read' => ['required_if:reads_source,server', 'string'],
            'previous_read' => ['required_if:reads_source,previous', 'string'],
            'uploaded_read' => ['required_if:reads_source,upload', 'file', 'max:5120000'], // 5GB limit
        ], [
            'uploaded_read.max' => 'The file size must not exceed 5GB.',
        ]);

        $user = Auth::user();
        $readsFile = '';
        $readsFileType = 'server';

        if ($request->reads_source === 'server') {
            $readsFile = $request->server_read;
            $readsFileType = 'server';
        } elseif ($request->reads_source === 'previous') {
            $readsFile = $request->previous_read;
            $readsFileType = 'uploaded';
        } elseif ($request->reads_source === 'upload') {
            $file = $request->file('uploaded_read');
            $originalName = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());

            // Validate format: fa, fasta, fastq, fastq.gz, fq
            // Since fastq.gz gets parsed by PHP as .gz extension, let's check original filename
            $validExtensions = ['fa', 'fasta', 'fastq', 'fq', 'gz'];
            $isGzipFastq = str_ends_with(strtolower($originalName), '.fastq.gz') || str_ends_with(strtolower($originalName), '.fq.gz');

            if (!in_array($ext, $validExtensions) && !$isGzipFastq) {
                return back()->withErrors(['uploaded_read' => 'Invalid file format. Only .fa, .fasta, .fastq, .fastq.gz, .fq are accepted.'])->withInput();
            }

            $destDir = '/Users/lathiifa/Documents/Capstone/isnip_data/reads';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }

            $storedName = time() . '_' . $originalName;
            $file->move($destDir, $storedName);

            $storedPath = $destDir . '/' . $storedName;
            $size = filesize($storedPath);

            // Record in user_uploads
            $userUpload = UserUpload::create([
                'user_id' => $user->id,
                'filename' => $originalName,
                'stored_path' => $storedPath,
                'size' => $size,
                'format' => $ext === 'gz' ? 'fastq.gz' : $ext,
            ]);

            $readsFile = $originalName; // Display original filename in job
            $readsFileType = 'uploaded';
        }

        // Generate Job ID e.g., #JOB-88421
        $maxJob = AnalysisJob::orderBy('id', 'desc')->first();
        $nextNum = $maxJob ? ($maxJob->id + 88401) : 88401;
        $jobId = "JOB-" . $nextNum;

        // Custom parameters mapping
        $advancedParams = [
            'sensitivity' => $request->input('sensitivity', 'standard'),
            'min_depth' => $request->input('min_depth', 10),
            'threads' => $request->input('threads', 8),
        ];

        $analysisJob = AnalysisJob::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'title' => $request->title,
            'alignment_index' => $request->alignment_index,
            'reads_type' => $request->reads_type,
            'reads_file' => $readsFile,
            'reads_file_type' => $readsFileType,
            'reference_sequence' => $request->reference_sequence,
            'annotation_db' => $request->annotation_db,
            'advanced_params' => $advancedParams,
            'status' => 'SUBMITTED',
            'current_step' => 'alignment',
            'submit_date' => Carbon::now(),
        ]);

        return redirect()->route('jobs')->with('job_created', 'Job #' . $jobId . ' successfully submitted and initialized.');
    }

    public function cancel($id)
    {
        $job = AnalysisJob::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        if ($job->status === 'SUBMITTED' || $job->status === 'RUNNING') {
            $job->status = 'CANCELED';
            $job->current_step = null;
            $job->logs .= "\n\n[WARNING] " . Carbon::now()->toDateTimeString() . " - Job execution canceled by user.";
            $job->save();
        }

        return back()->with('job_canceled', 'Job successfully canceled.');
    }

    /**
     * Exports a professional printable PDF report of the completed job.
     */
    public function downloadPdf($id)
    {
        $job = AnalysisJob::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        if ($job->status !== 'FINISHED') {
            return back()->withErrors(['error' => 'Reports can only be generated for finished jobs.']);
        }

        $pdf = Pdf::loadView('reports.pdf_report', compact('job'))->setPaper('a4', 'portrait');

        return $pdf->download($job->job_id . '_report.pdf');
    }
}
