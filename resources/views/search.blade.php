@extends('layouts.layout')

@section('page_title', 'Search')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-16">

    <!-- Header Description -->
    <div class="space-y-0.5">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Genomic Search</h1>
        <p class="text-sm font-medium text-slate-500">Query large-scale variant databases across multiple cohorts using precise chromosomal coordinates and clinical metadata filters.</p>
    </div>

    <!-- Search Query Panel -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden" 
         x-data="{ 
            conditions: {{ request('advanced') ? json_encode(request('advanced')) : '[]' }},
            addCondition() {
                this.conditions.push({ chrom: '', op: 'AND', value: '' });
            },
            removeCondition(index) {
                this.conditions.splice(index, 1);
            }
         }">
         
        <form action="{{ route('search') }}" method="GET" class="px-8 py-8 space-y-6">
            
            <!-- Standard Range Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                <!-- Data Source -->
                <div>
                    <label for="dataset_source" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Dataset Source <span class="text-red-500">*</span></label>
                    <select id="dataset_source" name="dataset_source" required class="block w-full px-3.5 py-2 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-700 font-semibold">
                        <option value="ALL">ALL DATABASES</option>
                        @foreach ($datasets as $db)
                            <option value="{{ $db }}" {{ request('dataset_source') == $db ? 'selected' : '' }}>{{ $db }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Position -->
                <div>
                    <label for="start_pos" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Start Position (BP)</label>
                    <input type="number" id="start_pos" name="start_pos" value="{{ request('start_pos') }}" placeholder="e.g. 150000" class="block w-full px-3.5 py-2 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <!-- End Position -->
                <div>
                    <label for="end_pos" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">End Position (BP)</label>
                    <input type="number" id="end_pos" name="end_pos" value="{{ request('end_pos') }}" placeholder="e.g. 175000" class="block w-full px-3.5 py-2 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <!-- Run Search Trigger -->
                <div>
                    <button type="submit" class="w-full flex items-center justify-center py-2.5 px-4 text-xs font-bold uppercase tracking-wider rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition duration-150 gap-2 h-[34px]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Run Search</span>
                    </button>
                </div>
            </div>

            <!-- Advanced Filters Accordion/Trigger -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <h4 class="text-[10px] uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <span>Advanced Filters</span>
                    </h4>
                    <button type="button" @click="addCondition()" class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-700 gap-1 transition">
                        <span>+ Add Condition</span>
                    </button>
                </div>

                <!-- Live Alpine conditions array editor -->
                <div class="space-y-3" x-show="conditions.length > 0" x-cloak>
                    <template x-for="(cond, index) in conditions" :key="index">
                        <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-3 rounded-xl shadow-inner">
                            <!-- Chromosome dropdown -->
                            <div class="w-1/4">
                                <select :name="`advanced[${index}][chrom]`" x-model="cond.chrom" class="block w-full px-2.5 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold focus:outline-none">
                                    <option value="">Select Chromosome</option>
                                    <option value="chr1">chr1</option>
                                    <option value="chr2">chr2</option>
                                    <option value="chr12">chr12</option>
                                    <option value="chr17">chr17</option>
                                </select>
                            </div>

                            <!-- Logical Operator -->
                            <div class="w-1/6">
                                <select :name="`advanced[${index}][op]`" x-model="cond.op" class="block w-full px-2.5 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold focus:outline-none">
                                    <option value="AND">AND</option>
                                    <option value="OR">OR</option>
                                </select>
                            </div>

                            <!-- Filter criteria box -->
                            <div class="flex-1">
                                <input type="text" :name="`advanced[${index}][value]`" x-model="cond.value" placeholder="e.g. Gene name or clinical significance..." class="block w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none">
                            </div>

                            <!-- Trash option -->
                            <button type="button" @click="removeCondition(index)" class="text-slate-400 hover:text-red-500 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </form>

    </div>

    <!-- Search Results Panel -->
    @if ($searchPerformed)
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Table Header stats and download triggers -->
            <div class="px-6 py-5 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
                <div class="space-y-0.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Search Results</h3>
                    <p class="text-xs font-bold text-slate-800 leading-none">
                        Found {{ count($results) }} variant matches in 0.42s
                    </p>
                </div>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-slate-200 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 rounded-xl transition gap-2 shadow-sm">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export CSV</span>
                </button>
            </div>

            <!-- Mapped Results Table -->
            <div class="overflow-x-auto">
                @if (empty($results))
                    <div class="text-center py-16 px-6 space-y-3">
                        <div class="mx-auto h-12 w-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-slate-900">No Variant Matches Found</h4>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Try broadening your coordinate ranges or removing condition criteria.</p>
                        </div>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-[9px] uppercase font-bold tracking-wider text-slate-400">
                                <th class="px-6 py-4">Job</th>
                                <th class="px-6 py-4">Chrom</th>
                                <th class="px-6 py-4">Position ID</th>
                                <th class="px-6 py-4">Ref</th>
                                <th class="px-6 py-4">Alt</th>
                                <th class="px-6 py-4">Qual</th>
                                <th class="px-6 py-4">Filter</th>
                                <th class="px-6 py-4">GT</th>
                                <th class="px-6 py-4">PL</th>
                                <th class="px-6 py-4">GQ</th>
                                <th class="px-6 py-4 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-[11px] font-semibold text-slate-700 font-mono">
                            @foreach ($results as $snp)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <!-- Job Link -->
                                    <td class="px-6 py-3.5 text-indigo-600 font-bold">
                                        <a href="{{ route('jobs.show', $snp['job_db_id']) }}" class="hover:underline">#{{ $snp['job_id'] }}</a>
                                    </td>

                                    <!-- Chromosome -->
                                    <td class="px-6 py-3.5 text-slate-800">
                                        {{ $snp['chrom'] }}
                                    </td>

                                    <!-- Position ID -->
                                    <td class="px-6 py-3.5 text-slate-500 font-semibold">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="text-slate-800 font-bold font-mono">{{ $snp['pos'] }}</span>
                                            <span class="text-[9px] text-slate-400 font-sans tracking-wide uppercase font-bold">{{ $snp['id'] }}</span>
                                        </div>
                                    </td>

                                    <!-- Ref -->
                                    <td class="px-6 py-3.5 text-slate-600">
                                        {{ $snp['ref'] }}
                                    </td>

                                    <!-- Alt -->
                                    <td class="px-6 py-3.5 text-slate-600">
                                        {{ $snp['alt'] }}
                                    </td>

                                    <!-- Qual -->
                                    <td class="px-6 py-3.5 text-slate-600 font-sans font-semibold">
                                        {{ $snp['qual'] }}
                                    </td>

                                    <!-- Filter badge -->
                                    <td class="px-6 py-3.5 font-sans">
                                        @if ($snp['filter'] === 'PASS')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-emerald-50 text-emerald-700 uppercase tracking-wide border border-emerald-100">PASS</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold bg-amber-50 text-amber-700 uppercase tracking-wide border border-amber-100">LOW_QUAL</span>
                                        @endif
                                    </td>

                                    <!-- GT -->
                                    <td class="px-6 py-3.5 text-slate-600">
                                        {{ $snp['gt'] }}
                                    </td>

                                    <!-- PL -->
                                    <td class="px-6 py-3.5 text-slate-400">
                                        {{ $snp['pl'] }}
                                    </td>

                                    <!-- GQ -->
                                    <td class="px-6 py-3.5 text-slate-600 font-sans font-semibold">
                                        {{ $snp['gq'] }}
                                    </td>

                                    <!-- Details View Action link -->
                                    <td class="px-6 py-3.5 text-right font-sans font-bold uppercase tracking-wider text-[10px]">
                                        <a href="{{ route('jobs.show', $snp['job_db_id']) }}" class="text-indigo-600 hover:text-indigo-700 transition">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    @endif

</div>
@endsection
