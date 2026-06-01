@extends('layouts.layout')

@section('page_title', 'Job Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20" x-data="{ 
    statsOpen: false, 
    activeStep: 1,
    status: '{{ $job->status }}',
    init() {
        if (this.status === 'RUNNING' || this.status === 'SUBMITTED') {
            setTimeout(() => window.location.reload(), 4000);
        }
    }
}">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('jobs') }}" class="h-9 w-9 rounded-xl border border-slate-200 hover:bg-slate-50 transition flex items-center justify-center text-slate-500 hover:text-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="space-y-0.5">
                <span class="text-[9px] uppercase font-bold tracking-widest text-indigo-600">Detailed Analysis View</span>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Job Details:</span>
                    <span class="text-slate-500 font-medium">{{ $job->title }}</span>
                </h1>
            </div>
        </div>
        
        <!-- Live Status Refresh indicator -->
        @if ($job->status === 'RUNNING' || $job->status === 'SUBMITTED')
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-200/50 rounded-full px-3 py-1 shadow-sm">
                <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Live Refreshing...</span>
            </div>
        @else
            <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-3.5 py-1.5 border border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-600 bg-white hover:bg-slate-50 rounded-xl transition gap-1.5 shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5"/>
                </svg>
                <span>Refresh</span>
            </a>
        @endif
    </div>

    <!-- Job Summary Information Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-100 pb-2.5">Job Summary</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 text-xs leading-relaxed font-semibold">
            <!-- Job ID -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Job ID</div>
                <div class="font-mono text-indigo-600 text-[13px] font-bold">#{{ $job->job_id }}</div>
            </div>

            <!-- Status -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Status</div>
                <div>
                    @if ($job->status === 'FINISHED')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wide border border-emerald-100">Finished</span>
                    @elseif ($job->status === 'RUNNING')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-sky-50 text-sky-700 uppercase tracking-wide border border-sky-100 animate-pulse">Running</span>
                    @elseif ($job->status === 'SUBMITTED')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wide border border-indigo-100">Submitted</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 uppercase tracking-wide border border-red-100">Canceled</span>
                    @endif
                </div>
            </div>

            <!-- Submit Date -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Submit Date</div>
                <div class="text-slate-800">{{ $job->submit_date->format('d M Y, H:i') }}</div>
            </div>

            <!-- Start Date -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Start Date</div>
                <div class="text-slate-800">{{ $job->start_date ? $job->start_date->format('d M Y, H:i') : '--' }}</div>
            </div>

            <!-- Finish Date -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Finish Date</div>
                <div class="text-slate-800">{{ $job->finish_date ? $job->finish_date->format('d M Y, H:i') : '--' }}</div>
            </div>

            <!-- Last Progress -->
            <div class="space-y-1">
                <div class="text-[9px] uppercase font-bold text-slate-400">Last Progress</div>
                <div class="text-slate-800 uppercase tracking-wider text-[10px] font-extrabold text-indigo-600">
                    {{ $job->status === 'FINISHED' ? 'Store_db' : ($job->current_step ? str_replace('_', ' ', $job->current_step) : 'queue_init') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Pipeline Execution Accordion List -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2.5">Pipeline Execution Progress</h3>

        @php
            $steps = [
                1 => [
                    'name' => 'Alignment',
                    'desc' => 'Read alignment mapping to reference genome index',
                    'db_key' => 'alignment',
                    'prev_key' => null,
                ],
                2 => [
                    'name' => 'Sorting',
                    'desc' => 'Samtools coordinate sorting and duplicate marking',
                    'db_key' => 'sorting',
                    'prev_key' => 'alignment',
                ],
                3 => [
                    'name' => 'SNP Calling',
                    'desc' => 'GATK HaplotypeCaller active region scanning and variant detection',
                    'db_key' => 'snp_calling',
                    'prev_key' => 'sorting',
                ],
                4 => [
                    'name' => 'SNP Filtering',
                    'desc' => 'Hard variant quality filtration score metrics pruning',
                    'db_key' => 'snp_filtering',
                    'prev_key' => 'snp_calling',
                ],
                5 => [
                    'name' => 'SNP Annotation',
                    'desc' => 'SnpEff mappings to ClinVar and gnomAD effect DBs',
                    'db_key' => 'snp_annotation',
                    'prev_key' => 'snp_filtering',
                ]
            ];
        @endphp

        @foreach ($steps as $num => $step)
            @php
                // Determine Step Status
                $stepStatus = 'WAITING';
                if ($job->status === 'FINISHED') {
                    $stepStatus = 'COMPLETED';
                } elseif ($job->status === 'CANCELED') {
                    $stepStatus = 'CANCELED';
                } elseif ($job->status === 'RUNNING') {
                    if ($job->current_step === $step['db_key']) {
                        $stepStatus = 'RUNNING';
                    } else {
                        // Check if current step is after this step
                        $allKeys = array_column($steps, 'db_key');
                        $currIndex = array_search($job->current_step, $allKeys);
                        $stepIndex = array_search($step['db_key'], $allKeys);
                        if ($currIndex > $stepIndex) {
                            $stepStatus = 'COMPLETED';
                        }
                    }
                }
            @endphp
            
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm transition">
                <!-- Accordion Header Clicker -->
                <button type="button" @click="activeStep = activeStep === {{ $num }} ? null : {{ $num }}" class="w-full flex items-center justify-between px-6 py-4 text-left transition select-none hover:bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <!-- Step circle indicator -->
                        <div class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] font-bold shadow-inner"
                             :class="activeStep === {{ $num }} ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500'">
                            {{ $num }}
                        </div>
                        <div class="space-y-0.5">
                            <h4 class="text-xs uppercase font-extrabold text-slate-800 tracking-wider">Step {{ $num }}: {{ $step['name'] }}</h4>
                            <p class="text-[10px] text-slate-400 font-semibold tracking-normal">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Step Status Badge -->
                        @if ($stepStatus === 'COMPLETED')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-emerald-50 text-emerald-700 uppercase tracking-wide border border-emerald-100">Completed</span>
                        @elseif ($stepStatus === 'RUNNING')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-sky-50 text-sky-700 uppercase tracking-wide border border-sky-100 animate-pulse">Running</span>
                        @elseif ($stepStatus === 'CANCELED')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-red-50 text-red-700 uppercase tracking-wide border border-red-100">Canceled</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-slate-50 text-slate-400 uppercase tracking-wide border border-slate-200/50">Waiting</span>
                        @endif
                        
                        <!-- Caret down -->
                        <svg class="h-4 w-4 text-slate-400 transform transition" :class="{'rotate-180': activeStep === {{ $num }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <!-- Expanded Content -->
                <div x-show="activeStep === {{ $num }}" x-collapse class="border-t border-slate-100 px-6 py-5 bg-slate-50/50 space-y-4">
                    @if ($stepStatus === 'WAITING')
                        <div class="text-center py-6 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                            Waiting for preceding stages to complete...
                        </div>
                    @else
                        <!-- Step details -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-[10px] uppercase font-bold text-slate-400 leading-normal">
                            <div>
                                <div>Start Date</div>
                                <div class="text-slate-700 font-bold mt-0.5">
                                    {{ $job->start_date ? $job->start_date->copy()->addSeconds(($num - 1) * 15)->format('d M Y, H:i:s') : '--' }}
                                </div>
                            </div>
                            <div>
                                <div>Finish Date</div>
                                <div class="text-slate-700 font-bold mt-0.5">
                                    @if ($stepStatus === 'COMPLETED')
                                        {{ $job->start_date ? $job->start_date->copy()->addSeconds($num * 15)->format('d M Y, H:i:s') : '--' }}
                                    @else
                                        --
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div>Alignment Output</div>
                                <div class="text-indigo-600 font-bold mt-0.5 truncate lowercase">
                                    @if ($num == 1)
                                        @ output.sam (1.2 GB)
                                    @elseif ($num == 2)
                                        @ sorted.bam (300 MB)
                                    @elseif ($num == 3)
                                        @ raw_variants.vcf (1.2 MB)
                                    @elseif ($num == 4)
                                        @ filtered_variants.vcf (850 KB)
                                    @else
                                        @ annotated.vcf (1.1 MB)
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div>Output File Path</div>
                                <div class="text-slate-600 font-bold mt-0.5 truncate font-mono text-[9px] lowercase">
                                    /isnip/jobs/{{ $job->job_id }}/output_{{ $step['db_key'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Console Logs terminal -->
                        <div class="space-y-1.5">
                            <div class="text-[9px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                <span>Analysis Console logs</span>
                            </div>
                            <div class="bg-slate-900 rounded-xl p-4 font-mono text-[10px] text-slate-300 leading-relaxed shadow-inner overflow-x-auto max-h-60">
                                <pre class="whitespace-pre-wrap">{{ $job->logs }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bottom Actions Panel -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            @if ($job->status === 'FINISHED')
                <button type="button" @click="statsOpen = true" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition shadow-sm">
                    View SNP Statistics
                </button>
            @else
                <button type="button" disabled class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl text-slate-400 bg-slate-100 cursor-not-allowed">
                    SNP Statistics Locked
                </button>
            @endif
        </div>
        <div>
            @if ($job->status === 'FINISHED')
                <a href="{{ route('jobs.download', $job->id) }}" class="inline-flex items-center justify-center px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download Report (PDF)</span>
                </a>
            @else
                <button type="button" disabled class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl text-slate-400 bg-slate-100 cursor-not-allowed flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Report Compiling</span>
                </button>
            @endif
        </div>
    </div>

    <!-- SNP Statistics Modal overlay -->
    <div x-show="statsOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="statsOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200/60"
                 x-show="statsOpen" x-transition:enter="ease-out duration-300">
                 
                <!-- Header banner -->
                <div class="bg-gradient-to-tr from-indigo-900 to-slate-900 px-6 py-5 text-white flex items-center justify-between">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-bold tracking-tight">SNP Distribution Statistics</h3>
                        <p class="text-[9px] text-slate-300 font-semibold uppercase tracking-wider">Job ID: #{{ $job->job_id }} Metrics</p>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-white transition" @click="statsOpen = false">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Stats details body -->
                <div class="p-6 space-y-6">
                    @if ($job->results_data)
                        <!-- Dynamic indicator stats grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl shadow-inner">
                                <div class="text-[8px] uppercase font-bold text-slate-400 mb-0.5">Total Mapped SNPs</div>
                                <div class="text-xl font-extrabold text-slate-800">{{ $job->results_data['stats']['total_variants'] }}</div>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl shadow-inner">
                                <div class="text-[8px] uppercase font-bold text-slate-400 mb-0.5">Transitions / Transversions</div>
                                <div class="text-xl font-extrabold text-slate-800">
                                    {{ $job->results_data['stats']['transitions'] }} / {{ $job->results_data['stats']['transversions'] }}
                                </div>
                                <div class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase">Ratio: {{ $job->results_data['stats']['ti_tv_ratio'] }}</div>
                            </div>
                        </div>

                        <!-- Bar chart indicators for Mutation Impact -->
                        <div class="space-y-3.5">
                            <h4 class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-2.5">Genomic Variant Impacts</h4>
                            
                            <!-- Missense Mutations -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                    <span>Missense Variants</span>
                                    <span>{{ $job->results_data['stats']['missense'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ ($job->results_data['stats']['missense'] / $job->results_data['stats']['total_variants']) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- Synonymous Mutations -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                    <span>Synonymous Variants</span>
                                    <span>{{ $job->results_data['stats']['synonymous'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-sky-500 h-full rounded-full" style="width: {{ ($job->results_data['stats']['synonymous'] / $job->results_data['stats']['total_variants']) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- High Impact Pathogenic counts -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                    <span>Nonsense / High Impact Variants</span>
                                    <span>{{ $job->results_data['stats']['nonsense'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ ($job->results_data['stats']['nonsense'] / $job->results_data['stats']['total_variants']) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Mini list of mapped SNPs -->
                        <div class="space-y-2 border-t border-slate-100 pt-4">
                            <h4 class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Identified SNP Variants</h4>
                            <div class="max-h-40 overflow-y-auto border border-slate-200/60 rounded-xl">
                                <table class="min-w-full divide-y divide-slate-100 text-[10px] leading-relaxed">
                                    <thead class="bg-slate-50 uppercase text-slate-400 font-bold">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Locus</th>
                                            <th class="px-4 py-2 text-left">rsID</th>
                                            <th class="px-4 py-2 text-left">Change</th>
                                            <th class="px-4 py-2 text-left">Gene</th>
                                            <th class="px-4 py-2 text-right">ClinSig</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-slate-700 font-semibold">
                                        @foreach ($job->results_data['snps'] as $snp)
                                            <tr>
                                                <td class="px-4 py-2 font-mono text-indigo-600">{{ $snp['chrom'] }}:{{ $snp['pos'] }}</td>
                                                <td class="px-4 py-2">{{ $snp['id'] }}</td>
                                                <td class="px-4 py-2">{{ $snp['ref'] }} &rarr; {{ $snp['alt'] }}</td>
                                                <td class="px-4 py-2 font-bold">{{ $snp['gene'] }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    @if (str_contains(strtolower($snp['sig']), 'pathogenic'))
                                                        <span class="text-red-600 font-bold">{{ $snp['sig'] }}</span>
                                                    @elseif (str_contains(strtolower($snp['sig']), 'benign'))
                                                        <span class="text-emerald-600 font-bold">{{ $snp['sig'] }}</span>
                                                    @else
                                                        <span class="text-slate-500 font-bold">{{ $snp['sig'] }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="button" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition" @click="statsOpen = false">
                        Close Statistics
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
