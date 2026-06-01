@extends('layouts.layout')

@section('page_title', 'Home')

@section('content')
<div class="max-w-6xl mx-auto space-y-12 pb-16">
    @if (session('logout_success'))
        <div class="max-w-6xl mx-auto px-4">
            <div class="p-3.5 bg-slate-900 text-slate-200 text-xs font-semibold rounded-2xl flex items-center justify-between shadow-xl border border-slate-800 animate-fade-in">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span>{{ session('logout_success') }}</span>
                </div>
                {{-- Tombol X kecil untuk menutup banner menggunakan Alpine.js --}}
                <button @click="$el.parentElement.parentElement.remove()" class="text-slate-400 hover:text-white transition text-sm font-bold px-2">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <!-- Premium Hero Section -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center gap-10 shadow-sm relative overflow-hidden">
        <!-- Abstract genomic background design -->
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none -mr-40 -mt-40"></div>
        
        <!-- Left Hero Statements -->
        <div class="flex-1 space-y-6 relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Integrated SNP <br class="hidden md:block">
                <span class="bg-gradient-to-r from-indigo-600 to-sky-500 bg-clip-text text-transparent">Identification Pipeline</span>
            </h1>
            <p class="text-sm md:text-base text-slate-500 leading-relaxed font-medium">
                A comprehensive web-based platform for identifying Single Nucleotide Polymorphisms (SNPs) from genomic data. Seamlessly integrate alignment, variant calling, and annotation into a single automated pipeline.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                {{-- Cek apakah user sudah login --}}
                @auth
                    <a href="{{ route('jobs') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-100 rounded-xl transition gap-2">
                        <span>Start Analysis</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @endauth

                {{-- Cek apakah user belum login --}}
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-100 rounded-xl transition gap-2">
                        <span>Start Analysis</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </a>
                @endguest
                <a href="{{ asset('docs/Modul_ISNIP2025.pdf') }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition duration-150 gap-2">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>User Manual</span>
                </a>
            </div>
        </div>
        
        <!-- Right Hero Graphics -->
        <div class="w-full md:w-96 flex-shrink-0 flex justify-center">
            <div class="relative w-72 h-44 rounded-2xl overflow-hidden border border-slate-200 shadow-xl bg-slate-900 flex items-center justify-center group">
                <!-- Abstract bioinformatics coding environment visual mockup -->
                <div class="absolute inset-0 font-mono text-[8px] text-emerald-400 p-4 leading-normal overflow-hidden select-none opacity-40">
                    <div># Running ISNIP-2026 Core Pipeline...</div>
                    <div class="text-indigo-400">[INFO] loading hg38 reference genome...</div>
                    <div class="text-indigo-400">[INFO] indexing chromosome regions...</div>
                    <div>bwa-mem2 mem -t 8 index/hg38 sample_R1.fq sample_R2.fq</div>
                    <div class="text-sky-400">[BWA] 98.4% reads mapped. samtools sort output...</div>
                    <div>gatk HaplotypeCaller -R index.fa -I sorted.bam -O raw.vcf</div>
                    <div class="text-amber-400">[GATK] 4,152 raw SNPs detected. hard filters applying...</div>
                    <div>snpeff ann -v ClinVar_2023-Oct filtered.vcf > final.vcf</div>
                    <div class="text-emerald-300">[SUCCESS] Annotation mapping completed.</div>
                </div>
                <!-- Premium glass cover overlay -->
                <div class="absolute inset-0 bg-slate-900/10 hover:bg-slate-900/0 transition duration-300"></div>
                <div class="absolute bottom-4 right-4 bg-indigo-600/90 text-white text-[9px] uppercase tracking-widest font-bold px-2.5 py-1 rounded-md backdrop-blur shadow">
                    AGY ENGINE V2.0
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Architecture Modules Section -->
    <div class="space-y-6">
        <div class="text-center md:text-left space-y-1">
            <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-600">Workflow Architecture</span>
            <h2 class="text-xl md:text-2xl font-extrabold tracking-tight text-slate-900">Precision Pipeline Modules</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Module 1: Alignment -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                <div class="space-y-4">
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Alignment</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                        High-throughput read mapping against reference genomes using industry-standard BWA-MEM or Bowtie2 algorithms with optimized parameters for clinical accuracy.
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-700 mt-6 gap-1">
                    <span>View Alignment Specs</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Module 2: Variant Calling -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                <div class="space-y-4">
                    <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Variant Calling</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                        Precision detection of germline and somatic SNPs using GATK HaplotypeCaller or DeepVariant. Supports multi-sample joint genotyping for population studies.
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-xs font-semibold text-sky-600 hover:text-sky-700 mt-6 gap-1">
                    <span>Algorithm Documentation</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Module 3: Annotation -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                <div class="space-y-4">
                    <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Annotation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                        Functional interpretation of variants using VEP and SnpEff, cross-referenced against ClinVar, dbSNP, and gnomAD databases for clinical relevance.
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-xs font-semibold text-purple-600 hover:text-purple-700 mt-6 gap-1">
                    <span>Database Catalog</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Form Panel -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden" id="contact-form">
        
        <!-- Header banner -->
        <div class="bg-indigo-900/5 border-b border-indigo-900/10 px-8 py-5 flex items-center justify-between">
            <div class="space-y-0.5">
                <h3 class="text-sm font-bold text-slate-900">Contact Us</h3>
                <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider">Have questions or suggestions? We'd love to hear from you.</p>
            </div>
            <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>

        <div class="px-8 py-8">
            
            @if (session('contact_success'))
                <!-- Premium Thank You View Card -->
                <div class="max-w-md mx-auto text-center space-y-6 py-6" x-data="{ open: true }" x-show="open">
                    <div class="mx-auto h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-lg font-bold text-slate-900">Thank You for Reaching Out!</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                            Your message has been successfully sent. Our team will review your feedback and get back to you shortly if a response is required.
                        </p>
                    </div>
                    
                    <!-- Form Submission Metadata table -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-[10px] uppercase font-bold text-slate-500 grid grid-cols-2 divide-x divide-slate-200/80 max-w-sm mx-auto shadow-inner">
                        <div class="px-2 space-y-1">
                            <div>Reference ID</div>
                            <div class="text-xs font-bold text-slate-800 tracking-tight">{{ session('ref_id') }}</div>
                        </div>
                        <div class="px-2 space-y-1">
                            <div>Timestamp</div>
                            <div class="text-xs font-bold text-slate-800 tracking-tight">{{ session('timestamp') }}</div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('home') }}#contact-form" class="inline-flex items-center justify-center px-6 py-2.5 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow shadow-indigo-100 transition" @click="open = false">
                            Send Another Message
                        </a>
                    </div>
                </div>
            @else
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50/70 border border-red-200/80 rounded-xl flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-red-700 leading-relaxed">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Name <span class="text-red-500">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="Your full name" class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Email <span class="text-red-500">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="you@email.com" class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Subject <span class="text-red-500">*</span></label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required placeholder="What is this regarding?" class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>

                    <!-- Message/Feedback -->
                    <div>
                        <label for="message" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Message/Feedback <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="5" required placeholder="Tell us what's on your mind..." class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"></textarea>
                    </div>

                    <!-- Submit Trigger -->
                    <div class="flex justify-center">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition duration-150 gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span>Send Message</span>
                        </button>
                    </div>
                </form>
            @endif

        </div>

    </div>

</div>
@endsection
