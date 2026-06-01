<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - ISNIP Genomic Analysis</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between py-12 px-6 bg-slate-50">

    <!-- Top Logo Panel -->
    <div class="flex justify-center flex-shrink-0">
        <div class="flex flex-col items-center">
            <div class="flex items-center gap-1.5">
                <div class="flex items-end gap-0.5 h-7">
                    <img src="{{ asset('images/logo-isnip.png') }}" alt="iSNIP Logo" class="h-full w-auto object-contain">
                </div>
                <span class="text-2xl font-bold tracking-tight text-slate-900">ISNIP</span>
            </div>
            <span class="text-[10px] uppercase tracking-widest font-semibold text-slate-400 mt-1">Genomic Research Platform</span>
        </div>
    </div>

    <!-- Register Box Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-[560px] my-auto">
        <div class="bg-white shadow-xl shadow-slate-100 rounded-2xl border border-slate-200/60 overflow-hidden">
            
            <!-- Premium Blue Banner Header -->
            <div class="bg-gradient-to-tr from-indigo-900 via-indigo-950 to-slate-900 px-8 py-8 text-center text-white relative">
                <!-- Background decoration lines -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:16px_16px]"></div>
                
                <h2 class="text-xl font-bold tracking-tight">Create Account</h2>
                <p class="text-xs font-semibold text-slate-300 mt-1.5 uppercase tracking-wider">Access the ISNIP Genomic Analysis Environment</p>
            </div>

            <div class="px-8 py-8">
                <!-- Error Alerts Banner -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50/70 border border-red-200/80 rounded-xl flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-red-700 leading-relaxed space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form class="space-y-4" action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. John Doe" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username <span class="text-red-500">*</span></label>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required placeholder="e.g. johndoe123" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="e.g. john.doe@gmail.com" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <input id="password" name="password" type="password" required placeholder="Min. 8 characters" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Re-enter password" class="block w-full px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <!-- Institutional Affiliation Dropdown -->
                    <div>
                        <label for="institution" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Institutional Affiliation <span class="text-red-500">*</span></label>
                        <select id="institution" name="institution" required class="block w-full px-3.5 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition text-slate-700 font-medium">
                            <option value="">Select institution...</option>
                            <option value="IPB University" {{ old('institution') == 'IPB University' ? 'selected' : '' }}>IPB University</option>
                            <option value="ITB" {{ old('institution') == 'ITB' ? 'selected' : '' }}>Institut Teknologi Bandung (ITB)</option>
                            <option value="UI" {{ old('institution') == 'UI' ? 'selected' : '' }}>Universitas Indonesia (UI)</option>
                            <option value="UGM" {{ old('institution') == 'UGM' ? 'selected' : '' }}>Universitas Gadjah Mada (UGM)</option>
                            <option value="Eijkman Institute" {{ old('institution') == 'Eijkman Institute' ? 'selected' : '' }}>Eijkman Institute for Molecular Biology</option>
                        </select>
                    </div>

                    <!-- HIPAA Terms Checkbox -->
                    <div class="pt-2">
                        <div class="flex items-start gap-2.5">
                            <input id="hipaa_agreement" name="hipaa_agreement" type="checkbox" required class="h-4.5 w-4.5 mt-0.5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition">
                            <label for="hipaa_agreement" class="text-xs text-slate-500 leading-relaxed font-semibold cursor-pointer">
                                I acknowledge the <span class="text-indigo-600">Data Processing Agreement</span> and confirm I am handling PHI in compliance with HIPAA standards.
                            </label>
                        </div>
                    </div>

                    <!-- Register Trigger -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 text-sm font-semibold rounded-xl text-white bg-indigo-900 hover:bg-indigo-950 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-md shadow-indigo-200 transition">
                            Register Account
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-xs font-semibold">
                    <span class="text-slate-500">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 ml-1">Log in.</a>
                </div>

            </div>

        </div>
    </div>

    <!-- Security & Compliance Footer -->
    <footer class="flex flex-col items-center justify-center gap-3 text-[10px] text-slate-400 font-semibold tracking-wider flex-shrink-0 mt-8">
        <div class="flex items-center gap-6">
            <span>HIPAA COMPLIANT</span>
            <span>GDPR CERTIFIED</span>
            <span>SOC 2 CERTIFIED</span>
        </div>
        <div class="text-[9px] font-medium text-slate-300">
            © 2026 ISNIP GENOMIC RESEARCH PLATFORM. CLINICAL PRECISION SECURED.
        </div>
    </footer>

</body>
</html>
