<?php

namespace App\Http\Controllers;

use App\Models\AnalysisJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $results = [];
        $searchPerformed = false;

        // Perform active updates of running jobs to ensure complete results
        if (Auth::check()) {
            // Perform active updates of running jobs to ensure complete results
            $activeJobs = AnalysisJob::where('user_id', $user->id)
                ->whereIn('status', ['SUBMITTED', 'RUNNING'])
                ->get();

            foreach ($activeJobs as $job) {
                $job->checkSimulationStatus();
            }
        }

        if ($request->has('dataset_source') || $request->has('start_pos') || $request->has('end_pos')) {
            $searchPerformed = true;

            $request->validate([
                'start_pos' => ['nullable', 'integer', 'min:0'],
                'end_pos' => ['nullable', 'integer', 'min:0'],
            ]);

            $source = $request->input('dataset_source');
            $start = $request->input('start_pos');
            $end = $request->input('end_pos');

            if (Auth::check()) {
                // Fetch all finished jobs milik user 
                $jobsQuery = AnalysisJob::where('user_id', $user->id)->where('status', 'FINISHED');
                if ($source && $source !== 'ALL') {
                    $jobsQuery->where('annotation_db', $source);
                }
                $jobs = $jobsQuery->get();
            } else {
                // Jika GUEST, koleksi kosong 
                $jobs = collect();
            }

            // Extract variants matching filters
            foreach ($jobs as $job) {
                $resultsData = $job->results_data;
                if ($resultsData && isset($resultsData['snps'])) {
                    foreach ($resultsData['snps'] as $snp) {
                        $match = true;

                        // Range matching
                        if ($start !== null && $snp['pos'] < $start) {
                            $match = false;
                        }
                        if ($end !== null && $snp['pos'] > $end) {
                            $match = false;
                        }

                        // Advanced criteria filtering
                        if ($request->has('advanced')) {
                            $adv = $request->input('advanced');
                            // adv structure: array of [chrom, operator, value]
                            if (is_array($adv)) {
                                foreach ($adv as $cond) {
                                    if (!empty($cond['chrom'])) {
                                        if (strtolower($snp['chrom']) !== strtolower($cond['chrom'])) {
                                            $match = false;
                                        }
                                    }
                                    // Additional criteria values if any
                                    if (!empty($cond['value'])) {
                                        $val = $cond['value'];
                                        if (strpos(strtolower($snp['gene']), strtolower($val)) === false &&
                                            strpos(strtolower($snp['sig']), strtolower($val)) === false) {
                                            $match = false;
                                        }
                                    }
                                }
                            }
                        }

                        if ($match) {
                            $results[] = [
                                'job_db_id' => $job->id,
                                'job_id' => $job->job_id,
                                'chrom' => $snp['chrom'],
                                'pos' => $snp['pos'],
                                'id' => $snp['id'],
                                'ref' => $snp['ref'],
                                'alt' => $snp['alt'],
                                'qual' => $snp['qual'],
                                'filter' => $snp['filter'],
                                'gt' => $snp['gt'],
                                'pl' => $snp['pl'],
                                'gq' => $snp['gq'],
                                'gene' => $snp['gene'],
                                'sig' => $snp['sig']
                            ];
                        }
                    }
                }
            }
        }

        if (Auth::check()) {
            // Available datasets in databases milik user 
            $datasets = AnalysisJob::where('user_id', $user->id)
                ->where('status', 'FINISHED')
                ->pluck('annotation_db')
                ->unique();
                
            if ($datasets->isEmpty()) {
                $datasets = collect(['ClinVar 2023-Oct', 'dbSNP 155', 'gnomAD v3.1.2']);
            }
        } else {
            // Jika GUEST, dataset default 
            $datasets = collect(['ClinVar 2023-Oct', 'dbSNP 155', 'gnomAD v3.1.2']);
        }

        return view('search', compact('results', 'searchPerformed', 'datasets'));
    }
}
