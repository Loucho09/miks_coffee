<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', "Mik's Coffee Shop") }}</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { 
                            sans: ['Outfit', 'sans-serif'],
                            serif: ['Instrument Serif', 'serif']
                        },
                        colors: {
                            stone: { 50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1', 400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c', 800: '#292524', 900: '#1c1917', 950: '#0c0a09' },
                            amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
                        }
                    }
                }
            }
        </script>

        @livewireStyles

        <script>
            // Theme Logic: Immediate execution to prevent flash
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 flex flex-col min-h-screen">
        
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md border-b border-stone-200/50 dark:border-stone-800/50 sticky top-16 z-30 transition-colors">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
            @if(Auth::user()->usertype !== 'admin')
                <footer class="bg-white dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 transition-colors mt-20">
                    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                            <div class="col-span-1 md:col-span-1">
                                <div class="flex flex-col mb-4">
                                    <h1 class="font-serif italic text-2xl text-stone-900 dark:text-white leading-none">Mik's</h1>
                                    <span class="font-bold text-[10px] uppercase tracking-[0.25em] text-amber-600">COFFEE</span>
                                </div>
                                <p class="text-sm text-stone-500 dark:text-stone-400 leading-relaxed">
                                    Crafting premium coffee experiences in Trece Martires since 2025.
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Explore</h3>
                                <ul class="space-y-3">
                                    <li><a href="{{ route('home') }}" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Menu</a></li>
                                    <li><a href="{{ route('rewards.index') }}" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Rewards</a></li>
                                    <li><a href="{{ route('orders.index') }}" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Track Order</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Connect</h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-blue-600 transition">Facebook</a></li>
                                    <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-pink-600 transition">Instagram</a></li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Stay Updated</h3>
                                <form class="flex gap-2">
                                    <input type="email" placeholder="Email" class="w-full px-4 py-2 border border-stone-200 dark:border-stone-700 rounded-xl bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 outline-none transition">
                                    <button class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 px-4 py-2 rounded-xl hover:bg-amber-600 dark:hover:bg-amber-500 hover:text-white text-sm font-bold transition">Go</button>
                                </form>
                            </div>
                        </div>
                        <div class="border-t border-stone-100 dark:border-stone-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-xs text-stone-400 font-medium">© 2025 Mik's Coffee Shop. Unit 2B, Brgy. Osorio.</p>
                            <div class="flex gap-6">
                                <a href="#" class="text-[10px] uppercase tracking-widest text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 transition">Privacy</a>
                                <a href="#" class="text-[10px] uppercase tracking-widest text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 transition">Terms</a>
                            </div>
                        </div>
                    </div>
                </footer>
            @else
                <footer class="bg-white dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 py-4 mt-auto">
                    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
                        <p class="text-[10px] text-stone-400 font-bold tracking-[0.2em] uppercase">MIK'S ADMIN PANEL</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[10px] text-stone-400 font-medium uppercase tracking-widest">System Online</span>
                        </div>
                    </div>
                </footer>
            @endif
        @endauth

        <button id="theme-toggle" type="button" 
                class="fixed bottom-6 right-6 z-50 p-3.5 rounded-2xl bg-white dark:bg-stone-800 text-stone-500 dark:text-stone-400 shadow-[0_8px_30px_rgb(0,0,0,0.12)] dark:shadow-none border border-stone-200 dark:border-stone-700 hover:scale-110 active:scale-95 transition-all focus:outline-none ring-offset-2 focus:ring-2 focus:ring-amber-500/50">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <script>
            var themeToggleBtn = document.getElementById('theme-toggle');
            var darkIcon = document.getElementById('theme-toggle-dark-icon');
            var lightIcon = document.getElementById('theme-toggle-light-icon');

            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                darkIcon.classList.toggle('hidden');
                lightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        </script>

        @livewireScripts
    </body>
</html>