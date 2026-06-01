<div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
    
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/60"
             x-show="modalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="bg-gradient-to-tr from-indigo-900 to-slate-900 px-6 py-5 text-white flex items-center justify-between">
                <div class="space-y-0.5">
                    <h3 class="text-base font-bold tracking-tight">Submit New Job</h3>
                    <p class="text-[10px] text-slate-300 font-semibold uppercase tracking-wider">Configure Genomic SNP Pipeline Params</p>
                </div>
                <button type="button" class="text-slate-400 hover:text-white transition" @click="modalOpen = false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5" x-data="{
                readsSource: 'server',
                readsType: 'Single-end',
                dragActive: false,
                uploadFileName: '',
                uploadFileSize: '',
                uploadError: '',
                validateFile(event) {
                    this.uploadError = '';
                    const file = event.target.files[0];
                    if (!file) return;

                    const name = file.name;
                    const size = file.size;
                    const ext = name.split('.').pop().toLowerCase();
                    const isGzipFastq = name.endsWith('.fastq.gz') || name.endsWith('.fq.gz');

                    const validExtensions = ['fa', 'fasta', 'fastq', 'fq', 'gz'];
                    if (!validExtensions.includes(ext) && !isGzipFastq) {
                        this.uploadError = 'Invalid file format. Only .fa, .fasta, .fastq, .fastq.gz, .fq are accepted.';
                        this.uploadFileName = '';
                        event.target.value = '';
                        return;
                    }

                    if (size > 5120 * 1024 * 1024) { // 5GB
                        this.uploadError = 'File size exceeds the 5GB limit.';
                        this.uploadFileName = '';
                        event.target.value = '';
                        return;
                    }

                    this.uploadFileName = name;
                    this.uploadFileSize = (size / (1024 * 1024)).toFixed(2) + ' MB';
                }
            }">
                @csrf
                
                <div>
                    <label for="title" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required placeholder="e.g. BP Bacteria" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="alignment_index" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alignment Index <span class="text-red-500">*</span></label>
                        <select id="alignment_index" name="alignment_index" required class="block w-full px-3 py-2 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-700 font-semibold">
                            <option value="BWA-MEM (Standard)">BWA-MEM (Standard)</option>
                            <option value="Bowtie2">Bowtie2 (Fast)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Reads Type <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2 border border-slate-200 p-1 rounded-xl bg-slate-50/50 max-w-max h-[34px]">
                            <label class="inline-flex items-center px-3.5 py-1 rounded-lg text-xs font-semibold cursor-pointer transition select-none"
                                   :class="readsType === 'Single-end' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'">
                                <input type="radio" name="reads_type" value="Single-end" x-model="readsType" class="sr-only">
                                <span>Single-end</span>
                            </label>
                            <label class="inline-flex items-center px-3.5 py-1 rounded-lg text-xs font-semibold cursor-pointer transition select-none"
                                   :class="readsType === 'Paired-end' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'">
                                <input type="radio" name="reads_type" value="Paired-end" x-model="readsType" class="sr-only">
                                <span>Paired-end</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="reference_sequence" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Reference Sequence <span class="text-red-500">*</span></label>
                    <select id="reference_sequence" name="reference_sequence" required class="block w-full px-3 py-2.5 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-700 font-semibold">
                        <option value="">Select reference sequence...</option>
                        @foreach ($serverGenomes as $genome)
                            <option value="{{ $genome }}">{{ $genome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Reads (FASTQ / FASTA) <span class="text-red-500">*</span></label>
                    <input type="hidden" name="reads_source" :value="readsSource">
                    
                    <div class="flex border border-slate-200 p-0.5 rounded-xl bg-slate-50/80 mb-3 divide-x divide-slate-200">
                        <button type="button" @click="readsSource = 'server'" class="flex-1 py-1.5 text-[11px] font-bold uppercase tracking-wider transition rounded-lg"
                                :class="readsSource === 'server' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'">
                            Server Files
                        </button>
                        <button type="button" @click="readsSource = 'previous'" class="flex-1 py-1.5 text-[11px] font-bold uppercase tracking-wider transition rounded-lg"
                                :class="readsSource === 'previous' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'">
                            User Library
                        </button>
                        <button type="button" @click="readsSource = 'upload'" class="flex-1 py-1.5 text-[11px] font-bold uppercase tracking-wider transition rounded-lg"
                                :class="readsSource === 'upload' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'">
                            Upload File
                        </button>
                    </div>

                    <div x-show="readsSource === 'server'" x-transition>
                        <select name="server_read" class="block w-full px-3 py-2.5 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none text-slate-700 font-semibold">
                            <option value="">Select read file from server...</option>
                            @foreach ($serverReads as $read)
                                <option value="{{ $read }}">{{ $read }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="readsSource === 'previous'" x-transition>
                        @if ($uploadedFiles->isEmpty())
                            <div class="text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200/80 text-xs font-semibold text-slate-400">
                                No previous uploads found.
                            </div>
                        @else
                            <select name="previous_read" class="block w-full px-3 py-2.5 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none text-slate-700 font-semibold">
                                <option value="">Select from library...</option>
                                @foreach ($uploadedFiles as $file)
                                    <option value="{{ $file->filename }}">{{ $file->filename }} ({{ number_format($file->size / (1024 * 1024), 2) }} MB)</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div x-show="readsSource === 'upload'" x-transition>
                        <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-2xl p-6 text-center transition bg-slate-50/50"
                             :class="{'border-indigo-500 bg-indigo-50/30': dragActive}"
                             @dragover.prevent="dragActive = true"
                             @dragleave.prevent="dragActive = false"
                             @drop.prevent="dragActive = false; $refs.fileInput.files = $event.dataTransfer.files; validateFile({target: $refs.fileInput})">
                             
                            <input type="file" ref="fileInput" name="uploaded_read" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="validateFile($event)">
                            
                            <div class="space-y-2">
                                <div class="mx-auto h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="text-xs font-bold text-slate-700">Drag & drop files or <span class="text-indigo-600 underline">browse</span></div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">FASTQ, FASTA, FA, FQ, or FASTQ.GZ (Max 5GB)</p>
                            </div>
                        </div>

                        <div class="mt-2.5 space-y-1" x-show="uploadFileName || uploadError" x-cloak>
                            <div class="flex items-center justify-between text-xs font-semibold bg-indigo-50/50 border border-indigo-100 rounded-xl px-4 py-2" x-show="uploadFileName">
                                <span class="text-indigo-900 truncate" x-text="uploadFileName"></span>
                                <span class="text-indigo-600 text-[10px]" x-text="uploadFileSize"></span>
                            </div>
                            <div class="p-3 bg-red-50/70 border border-red-200/80 text-xs font-semibold text-red-700 rounded-xl leading-relaxed" x-show="uploadError" x-text="uploadError"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="annotation_db" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Annotation Database <span class="text-red-500">*</span></label>
                    <select id="annotation_db" name="annotation_db" required class="block w-full px-3 py-2.5 text-xs bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none text-slate-700 font-semibold">
                        <option value="ClinVar 2023-Oct">ClinVar 2023-Oct</option>
                        <option value="dbSNP 155">dbSNP 155</option>
                        <option value="gnomAD v3.1.2">gnomAD v3.1.2</option>
                    </select>
                </div>

                <div x-data="{ advancedOpen: false }">
                    <button type="button" @click="advancedOpen = !advancedOpen" class="w-full flex items-center justify-between py-2 text-[10px] font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-700 transition">
                        <span>Advanced Program Parameters</span>
                        <svg class="h-4 w-4 transform transition" :class="{'rotate-180': advancedOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="advancedOpen" x-transition class="pt-4 space-y-4 border-t border-slate-100 mt-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="sensitivity" class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Sensitivity Mode</label>
                                <select id="sensitivity" name="sensitivity" class="block w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg text-slate-700 font-semibold">
                                    <option value="standard">Standard</option>
                                    <option value="high">High Precision</option>
                                    <option value="low">Fast Scanning</option>
                                </select>
                            </div>
                            <div>
                                <label for="min_depth" class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Min Coverage Depth</label>
                                <input type="number" id="min_depth" name="min_depth" value="10" min="1" class="block w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg text-slate-700 font-semibold">
                            </div>
                        </div>
                        <div>
                            <label for="threads" class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">CPU Thread Allocation</label>
                            <input type="range" id="threads" name="threads" min="1" max="16" value="8" class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                   x-on:input="$refs.threadVal.innerText = $event.target.value">
                            <div class="flex justify-between text-[8px] font-bold text-slate-400 mt-1 uppercase">
                                <span>1 Core</span>
                                <span class="text-indigo-600" x-ref="threadVal">8 Cores</span>
                                <span>16 Cores</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition" @click="modalOpen = false">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 rounded-xl transition">
                        Create Job
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>