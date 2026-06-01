<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ISNIP Genomic Analysis</title>
    
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

    <!-- Login Box Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-[460px] my-auto">
        <div class="bg-white px-8 py-10 shadow-xl shadow-slate-100 rounded-2xl border border-slate-200/60">
            
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Login</h2>
                <p class="text-sm text-slate-500 mt-1.5 font-medium">Please fill out the following form with your login credentials.</p>
            </div>

            <!-- Error Alerts banner -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50/70 border border-red-200/80 rounded-xl flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-red-700 leading-relaxed">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                </div>
            @endif

            <form class="space-y-5" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Username / Email Field -->
                <div>
                    <label for="login" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Username or Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="login" name="login" type="text" value="{{ old('login') }}" required placeholder="e.g. johndoe123" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Password <span class="text-red-500">*</span></label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required placeholder="••••••••" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>
                </div>

                <!-- Remember and Forgot options -->
                <div class="flex items-center justify-between text-xs font-semibold">
                    <div class="flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition">
                        <label for="remember" class="text-slate-600 cursor-pointer">Remember me next time</label>
                    </div>
                    <div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700">Forgot?</a>
                    </div>
                </div>

                <!-- Login Trigger -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-md shadow-indigo-200 transition">
                        Log In
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-xs font-semibold">
                <span class="text-slate-500">Don't Have an Account?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 ml-1">Register here.</a>
            </div>

        </div>
    </div>

    <!-- Security & Compliance Footer -->
    <footer class="flex flex-col items-center justify-center gap-3 text-[10px] text-slate-400 font-semibold tracking-wider flex-shrink-0 mt-8">
        <div class="flex items-center gap-6">
            <span>PRIVACY POLICY</span>
            <span>TERMS OF SERVICE</span>
            <span>HIPAA COMPLIANCE</span>
            <span>SECURITY STANDARDS</span>
        </div>
        <div class="text-[9px] font-medium text-slate-300">
            © 2026 ISNIP GENOMIC RESEARCH PLATFORM. CLINICAL PRECISION SECURED.
        </div>
    </footer>

</body>
</html>
