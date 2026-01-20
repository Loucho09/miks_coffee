<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', "Mik's Coffee Shop - Best Coffee in Trece Martires") }}</title>
    
    <meta name="description" content="Visit Mik's Coffee Shop in Brgy. Osorio, Trece Martires. Premium coffee and savory meals.">
    <link rel="icon" type="image/png" href="{{ asset('/favicon.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- config.js -->
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: { sans: ['Outfit', 'sans-serif'] },
             colors: {
                // 🟢 SOPHISTICATED BEIGE & ESPRESSO THEME (60-30-10 Rule)
                stone: {
                    // LIGHT MODE: The 60% and 30% Beige connection
                    50: '#F5F2EA',  // 60% - Main Beige Background (Soft & Warm)
                    100: '#EBE6D9', // 30% - Secondary/Card Background
                    200: '#DED7C5', // 10% - Subtle Beige Borders
                    
                    // NEUTRAL STEPS: Typography and Accents
                    300: '#D1C8B1',
                    400: '#B8AD91',
                    500: '#8F8366',
                    600: '#736852',
                    700: '#574F3E',
                    800: '#3B352A',
                    
                    // DARK MODE: The "Connected" Charcoal-Beige
                    900: '#1A1816', // 30% - Secondary Dark BG (Warm Charcoal)
                    950: '#0C0B0A', // 60% - Primary Dark BG (Deep Espresso)
                    1000: '#FF0000', // Custom Red Preserved
                },
                
                // 🟢 ACCENT COLORS: 10% Branding
                amber: {
                    400: '#FBBF24',
                    500: '#F59E0B', // Primary 10% Accent (Brand Orange)
                    600: '#D97706',
                    700: '#B45309',
                    1000: '#F59E0B',
                },

                // 🟢 COFFEE SPECIFIC PALETTE
                'coffee': {
                    100: '#F5E6E0',
                    600: '#8D5F46',
                    800: '#4B2C20',
                    900: '#2C1810',
                },

                // 🟢 LEGACY BRANDING: Compatibility
                'brand': {
                    orange: '#F59E0B',
                },

                'dashboard': {
                    1000: '#FF0000',
                },
            },
            boxShadow: {
                // 🟢 NEW FEATURE: Custom Depth Effects
                // Soft shadow tuned for light beige backgrounds
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)',
                // Heavy, atmospheric shadow for dark theme depth
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)',
            },
            letterSpacing: {
                // For that premium high-end look on headers
                'widest': '0.4em',
            },
        }
        }
    }
</script>   

    @livewireStyles

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-stone-100  dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 flex flex-col min-h-screen">
    
    @include('layouts.navigation')

    @if (isset($header))
        <header class="bg-stone-100 /80 dark:bg-stone-900/80 backdrop-blur-md border-b border-stone-200/50 dark:border-stone-800/50 sticky top-16 z-30 transition-colors">
            <div class="max-w-7xl mx-auto py-3.5 px-4 sm:px-6 lg:px-">
                <div class="font-serif italic text-3xl text-stone-900 dark:text-white leading-tight">
                    {{ $header }}
                </div>
            </div>
        </header>
    @endif

    <main class="flex-grow relative">
        {{ $slot }}
    </main>

    @auth
        @if(Auth::user()->usertype === 'admin')
            <footer class="bg-stone-100 dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 py-6 mt-auto">
                <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-stone-900 dark:bg-stone-800 flex items-center justify-center">
                            <img src="{{ asset('/favicon.png') }}" alt="Logo" class="w-5 h-5">
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-stone-500">Mik's Coffee Admin Panel</span>
                    </div>
                    <div class="flex items-center gap-3 text-[10px] font-bold text-stone-400 uppercase tracking-widest">
                        @php
                            // 🔧 FIX: Check actual database status for admin
                            $adminUser = Auth::user();
                            $isActuallyOnline = $adminUser->is_online && $adminUser->last_seen_at && $adminUser->last_seen_at->diffInMinutes(now()) < 5;
                        @endphp
                        <span class="w-2 h-2 rounded-full {{ $isActuallyOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        <span>Management System {{ $isActuallyOnline ? 'Online' : 'Offline' }}</span>
                        <span class="mx-2 text-stone-200 dark:text-stone-800">|</span>
                        <span>© 2025</span>
                    </div>
                </div>
            </footer>
        @else
            <footer class="bg-stone-100 dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 transition-colors mt-20">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                        <div class="col-span-1">
                            <h1 class="font-serif italic text-2xl text-stone-900 dark:text-white mb-1">Mik's</h1>
                            <span class="font-bold text-[10px] uppercase tracking-[0.25em] text-amber-600">COFFEE</span>
                            <p class="text-sm text-stone-500 dark:text-stone-400 mt-4 leading-relaxed font-light">Premium coffee in Trece Martires.</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase mb-6 tracking-widest">Explore</h3>
                            <ul class="space-y-3 text-sm text-stone-500">
                                <li><a href="{{ route('home') }}" class="hover:text-amber-600 transition">Menu</a></li>
                                <li><a href="{{ route('rewards.index') }}" class="hover:text-amber-600 transition">Rewards</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase mb-6 tracking-widest">Support</h3>
                            <ul class="space-y-3 text-sm text-stone-500">
                                <li><a href="{{ route('support.index') }}" class="hover:text-amber-600 transition">Contact Us</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase mb-6 tracking-widest">Stay Updated</h3>
                            <div class="flex gap-2">
                                <input type="email" placeholder="Email" class="w-full px-4 py-2 border border-stone-200 dark:border-stone-700 rounded-xl bg-stone-100  dark:bg-stone-800 text-sm outline-none">
                                <button class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 px-4 py-2 rounded-xl font-bold">Go</button>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-stone-100 dark:border-stone-800 pt-8 text-xs text-stone-400 font-bold uppercase tracking-widest">
                        © 2025 Mik's Coffee Shop. Unit 2B, Brgy. Osorio.
                    </div>
                </div>
            </footer>
        @endif
    @endauth

    <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3.5 rounded-2xl bg-white dark:bg-stone-800 text-stone-500 dark:text-stone-400 shadow-2xl border border-stone-200 dark:border-stone-700 hover:scale-110 active:scale-95 transition-all outline-none ring-2 ring-amber-500/50">
        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </button>

    <script>
        var themeToggleBtn = document.getElementById('theme-toggle');
        var darkIcon = document.getElementById('theme-toggle-dark-icon');
        var lightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) { lightIcon.classList.remove('hidden'); } else { darkIcon.classList.remove('hidden'); }

        themeToggleBtn.addEventListener('click', function() {
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>
    
    @livewireScripts
</body>
</html>