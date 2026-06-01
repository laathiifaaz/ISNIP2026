<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ISNIP Genomic Analysis' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles for Scrollbars and Premium Typography -->
    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom Premium Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800 antialiased overflow-hidden">

    <!-- Root Dashboard Container -->
    <div class="flex h-full w-full overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar Navigation -->
        <aside class="hidden md:flex md:flex-col md:w-64 bg-white border-r border-slate-200/80 flex-shrink-0">
            <!-- Branding Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-200/80 gap-3">
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <!-- DNA Band Logo -->
                        <div class="flex items-end gap-0.5 h-6">
                            <img src="{{ asset('images/logo-isnip.png') }}" alt="iSNIP Logo" class="h-full w-auto object-contain">
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">ISNIP</span>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider font-semibold text-slate-400 -mt-0.5">Genomic Analysis Platform</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition duration-150 gap-3 {{ Route::is('home') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Home</span>
                </a>
                
                <a href="{{ route('search') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition duration-150 gap-3 {{ Route::is('search') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search</span>
                </a>
                
                <a href="{{ route('jobs') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition duration-150 gap-3 {{ Route::is('jobs') || Route::is('jobs.show') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>My Jobs</span>
                </a>
                
                <a href="{{ route('contact') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition duration-150 gap-3 {{ Route::is('contact') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Contact Us</span>
                </a>
            </nav>
            
            <!-- Quick System Diagnostics Card -->
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50 m-4 rounded-xl border">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-slate-700">Cluster Status: Active</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Simulated execution pipeline active.</p>
            </div>
        </aside>
        
        <!-- Main Panel -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Header -->
            <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-10 flex-shrink-0">
                <!-- Left path tag -->
                <div class="flex items-center gap-3">
                    <button class="md:hidden text-slate-500 hover:text-slate-900" @click="sidebarOpen = !sidebarOpen">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="text-sm font-medium text-slate-500 flex items-center gap-1.5">
                        <span>ISNIP</span>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-semibold">
                            @yield('page_title', 'Genomic Analysis')
                        </span>
                    </div>
                </div>
                
                <!-- Right Profiling Section -->
                <div class="flex items-center gap-4" x-data="{ profileOpen: false, notifyOpen: false }">
                    
                    <!-- Notification Bell -->
                    <div class="relative" x-data="{ notifyOpen: false }">
                        <button class="text-slate-400 hover:text-slate-600 transition" @click="notifyOpen = !notifyOpen">
                            <span class="absolute top-0.5 right-0.5 block h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>
                        <!-- Dropdown Notifications -->
                        <div class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl py-2 z-50 text-xs" x-show="notifyOpen" @click.away="notifyOpen = false" x-cloak>
                            <div class="px-4 py-2 border-b border-slate-100 font-semibold text-slate-700">Notifications</div>
                            <div class="divide-y divide-slate-100">
                                <div class="px-4 py-3 hover:bg-slate-50 flex flex-col gap-0.5">
                                    <span class="font-medium text-slate-800">Job Completion</span>
                                    <span class="text-slate-500 text-[10px]">Your job #JOB-88421 has completed successfully.</span>
                                </div>
                                <div class="px-4 py-3 hover:bg-slate-50 flex flex-col gap-0.5">
                                    <span class="font-medium text-slate-800">Database Synchronized</span>
                                    <span class="text-slate-500 text-[10px]">ClinVar 2023-Oct variants index updated.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vertical Divider -->
                    <span class="h-6 w-px bg-slate-200"></span>
                    
                    <!-- Profile card -->
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button class="flex items-center gap-3 text-left focus:outline-none" @click="profileOpen = !profileOpen">
                            <div class="flex flex-col text-right">
                                @auth
                                    {{-- Jika sudah login, tampilkan nama user asli --}}
                                    <span class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-indigo-600 mt-[-2px]">{{ Auth::user()->institution ?? 'CHIEF RESEARCHER' }}</span>
                                @endauth

                                @guest
                                    {{-- Jika belum login (Guest Mode), tampilkan profil tamu --}}
                                    <span class="text-sm font-semibold text-slate-800">Guest Researcher</span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-amber-600 mt-[-2px]">General</span>
                                @endguest
                            </div>
                            <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm border border-indigo-700/20">
                                @auth
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                @endauth
                                @guest
                                    GR
                                @endguest
                            </div>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-1.5 z-50 text-sm" x-show="profileOpen" @click.away="profileOpen = false" x-cloak>
                            @auth
                                {{-- Konten Dropdown untuk User yang Sudah Login --}}
                                <div class="px-4 py-1.5 border-b border-slate-100 text-xs text-slate-400 font-medium">Logged in as {{ Auth::user()->username }}</div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 flex items-center gap-2 font-medium">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Logout Session</span>
                                    </button>
                                </form>
                            @endauth

                            @guest
                                {{-- Konten Dropdown untuk Tamu / Guest (Ditawari menu Login) --}}
                                <div class="px-4 py-1.5 border-b border-slate-100 text-xs text-slate-400 font-medium">You are in Guest Mode</div>
                                <a href="{{ route('login') }}" class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-indigo-600 flex items-center gap-2 font-medium transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Sign In Account</span>
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dynamic Content Area -->
            <main class="flex-1 overflow-y-auto p-8 relative">
                @yield('content')
            </main>
            
        </div>
        
    </div>

</body>
</html>
