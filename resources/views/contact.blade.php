@extends('layouts.layout')

@section('page_title', 'Contact Us')

@section('content')
<div class="max-w-xl mx-auto pb-16">

    <!-- Contact Form Panel -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-indigo-900/5 border-b border-indigo-900/10 px-8 py-5 flex items-center justify-between">
            <div class="space-y-0.5">
                <h3 class="text-sm font-bold text-slate-900">Contact Us</h3>
                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Have questions or suggestions? We'd love to hear from you.</p>
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
                <div class="text-center space-y-6 py-6">
                    <div class="mx-auto h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-base font-bold text-slate-900">Thank You for Reaching Out!</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                            Your message has been successfully sent. Our team will review your feedback and get back to you shortly if a response is required.
                        </p>
                    </div>
                    
                    <!-- Metadata table -->
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
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow shadow-indigo-100 transition">
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
                        <textarea id="message" name="message" rows="6" required placeholder="Tell us what's on your mind..." class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"></textarea>
                    </div>

                    <!-- Submit Trigger -->
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition duration-150 gap-2">
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
