@extends('layouts.layout')

@section('page_title', 'My Jobs')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-16" x-data="{ modalOpen: false }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-0.5">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">My Job List</h1>
            <p class="text-sm font-medium text-slate-500">Monitor and manage your genomic sequencing pipelines.</p>
        </div>
        <div>
            {{-- MODIFIKASI LANGKAH 3: PENGKONDISIAN GUEST MODE PADA TOMBOL NEW JOB --}}
            @auth
                <button @click="modalOpen = true" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Job</span>
                </button>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Job</span>
                </a>
            @endguest
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Total Analyses</span>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ sprintf('%03d', $totalAnalyses) }}</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-emerald-50 text-emerald-700 mt-1 uppercase tracking-wide">
                    +12% vs last month
                </span>
            </div>
            <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2zm12 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2zm0 0v-7a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Currently Running</span>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ sprintf('%02d', $currentlyRunning) }}</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-sky-50 text-sky-700 mt-1 uppercase tracking-wide">
                    Across 3 clusters
                </span>
            </div>
            <div class="h-12 w-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Storage Used</span>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ $storageUsed }}</h3>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full" style="width: {{ $storagePercentage }}%"></div>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">{{ $storagePercentage }}% Capacity</span>
                </div>
            </div>
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Average Runtime</span>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ $averageRuntime }}m</h3>
                
                @if($runtimeDifference < 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-emerald-50 text-emerald-700 mt-1 uppercase tracking-wide">
                        {{ $runtimeDifference }}m from last week
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-rose-50 text-rose-700 mt-1 uppercase tracking-wide">
                        +{{ $runtimeDifference }}m from last week
                    </span>
                @endif
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    @if (session('job_created'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 text-xs font-semibold text-emerald-800 rounded-2xl flex items-center gap-2">
            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('job_created') }}</span>
        </div>
    @endif
    @if (session('job_canceled'))
        <div class="p-4 bg-amber-50 border border-amber-200/80 text-xs font-semibold text-amber-800 rounded-2xl flex items-center gap-2">
            <svg class="h-5 w-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>{{ session('job_canceled') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <form action="{{ route('jobs') }}" method="GET" class="relative w-full md:w-80">
                <input type="hidden" name="tab" value="{{ $statusTab }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Job ID or Title..." class="block w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-800 font-medium">
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ request()->fullUrl() }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 rounded-xl shadow-sm transition-all duration-200 gap-2 group">
                    <svg class="h-4 w-4 text-slate-400 group-hover:rotate-180 transition-transform duration-500 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.253 8H18"/>
                    </svg>
                    <span>Refresh</span>
                 </a>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 rounded-xl transition gap-2">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>

        <div class="flex border-b border-slate-200 overflow-x-auto">
            @php
                $tabs = [
                    'all' => 'Show All',
                    'submitted' => 'Submitted',
                    'running' => 'Running',
                    'finished' => 'Finished',
                    'canceled' => 'Canceled'
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                <a href="{{ route('jobs', ['tab' => $key, 'search' => request('search')]) }}" class="px-6 py-4 text-xs font-bold uppercase tracking-wider border-b-2 transition duration-150 whitespace-nowrap {{ $statusTab === $key ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-slate-500 hover:text-slate-900 hover:border-slate-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            @if ($jobs->isEmpty())
                <div class="text-center py-16 px-6 space-y-3">
                    <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-slate-900">No Jobs Found</h4>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Try adjusting your filters or submit a new job.</p>
                    </div>
                </div>
            @else
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[9px] uppercase font-bold tracking-wider text-slate-400">
                            <th class="px-6 py-4">Job ID</th>
                            <th class="px-6 py-4">Title & Pipeline</th>
                            <th class="px-6 py-4">Submit Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @foreach ($jobs as $job)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4.5 font-mono text-indigo-600 font-bold">
                                    #{{ $job->job_id }}
                                </td>
                                
                                <td class="px-6 py-4.5">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-bold text-slate-900 leading-tight">{{ $job->title }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium tracking-wide uppercase">{{ $job->alignment_index }} Index & Variant Calling</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4.5 text-slate-500 font-semibold">
                                    {{ $job->submit_date->format('M d, Y • H:i') }}
                                </td>

                                <td class="px-6 py-4.5">
                                    @if ($job->status === 'FINISHED')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wide border border-emerald-200/50 gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Finished</span>
                                        </span>
                                    @elseif ($job->status === 'RUNNING')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-sky-50 text-sky-700 uppercase tracking-wide border border-sky-200/50 gap-1.5">
                                            <span class="flex h-1.5 w-1.5 relative">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-sky-500"></span>
                                            </span>
                                            <span>Running</span>
                                        </span>
                                    @elseif ($job->status === 'SUBMITTED')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wide border border-indigo-200/50 gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                            <span>Submitted</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 uppercase tracking-wide border border-red-200/50 gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            <span>Canceled</span>
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4.5 text-right font-bold uppercase tracking-wider text-[10px]">
                                    <div class="flex items-center justify-end gap-3.5">
                                        @if ($job->status === 'SUBMITTED' || $job->status === 'RUNNING')
                                            @auth
                                                <form action="{{ route('jobs.cancel', $job->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="openCancelModal(event, this)" class="text-xs font-semibold text-red-600 hover:text-red-700 transition">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endauth
                                        @endif
                                        <a href="{{ route('jobs.show', $job->id) }}" class="text-indigo-600 hover:text-indigo-700 transition">View Details</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($jobs->hasPages())
            <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $jobs->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

    @include('jobs.submit_modal')

    <div id="cancelJobModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
    
        <div class="relative w-full max-w-md mx-auto my-6 p-4 z-50">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-2xl shadow-xl outline-none focus:outline-none p-6 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex flex-col items-center justify-center text-center pt-2">
                    <div class="h-12 w-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Cancel Analysis Run</h3>
                    <p class="text-sm text-slate-500 mt-2 px-4">Are you sure you want to cancel this analysis run? This action cannot be undone.</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-6">
                    <button type="button" onclick="closeCancelModal()" class="w-full px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all duration-150">
                        No, Keep Running
                    </button>
                    <button type="button" id="confirmCancelBtn" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-all duration-150">
                        Yes, Cancel It
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let activeForm = null;

        function openCancelModal(event, button) {
            event.preventDefault(); 
            activeForm = button.closest('form'); 
            
            const modal = document.getElementById('cancelJobModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; 
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelJobModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
            activeForm = null;
        }

        // Eksekusi submit form ketika tombol "Yes, Cancel It" ditekan
        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (activeForm) {
                activeForm.submit();
            }
        });
    </script>

</div>
@endsection