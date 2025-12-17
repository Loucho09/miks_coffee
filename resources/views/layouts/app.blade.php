<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Miks Coffee') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700&display=swap" rel="stylesheet" />
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Outfit', 'sans-serif'] },
                        colors: {
                            stone: { 50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1', 400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c', 800: '#292524', 900: '#1c1917', 950: '#0c0a09' },
                            amber: { 600: '#d97706', 700: '#b45309' }
                        }
                    }
                }
            }
        </script>
        <script>
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
            <header class="bg-white dark:bg-stone-900 shadow-sm border-b border-stone-100 dark:border-stone-800 transition-colors">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-grow">
            {{ $slot }}
        </main>

        @if(Auth::user()->usertype !== 'admin')
            <footer class="bg-white dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 transition-colors mt-auto">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">About Us</h3>
                            <ul class="space-y-3">
                                <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Our Story</a></li>
                                <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Locations</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Order</h3>
                            <ul class="space-y-3">
                                <li><a href="{{ route('home') }}" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">Menu</a></li>
                                <li><a href="{{ route('cart.index') }}" class="text-sm text-stone-500 dark:text-stone-400 hover:text-amber-600 transition">My Cart</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Connect</h3>
                            <ul class="space-y-3">
                                <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-blue-600 transition">Facebook</a></li>
                                <li><a href="#" class="text-sm text-stone-500 dark:text-stone-400 hover:text-pink-600 transition">Instagram</a></li>
                            </ul>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Newsletter</h3>
                            <form class="flex gap-2">
                                <input type="email" placeholder="Email" class="w-full px-4 py-2 border border-stone-200 dark:border-stone-700 rounded-lg bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white text-sm">
                                <button class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 text-sm font-bold">Go</button>
                            </form>
                        </div>
                    </div>
                    <div class="border-t border-stone-200 dark:border-stone-800 pt-8 text-center">
                        <p class="text-sm text-stone-400">© 2025 Mik's Coffee Shop. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        @else
            <footer class="bg-white dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 py-6 mt-auto">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <p class="text-xs text-stone-400 font-medium tracking-wide">MIK'S COFFEE ADMIN PANEL • SECURE AREA</p>
                </div>
            </footer>
        @endif

        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-stone-800 text-stone-500 dark:text-stone-400 shadow-xl hover:bg-stone-100 dark:hover:bg-stone-700 transition-all focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <script>
            var themeToggleBtn = document.getElementById('theme-toggle');
            var darkIcon = document.getElementById('theme-toggle-dark-icon');
            var lightIcon = document.getElementById('theme-toggle-light-icon');
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) { lightIcon.classList.remove('hidden'); } else { darkIcon.classList.remove('hidden'); }
            themeToggleBtn.addEventListener('click', function() {
                darkIcon.classList.toggle('hidden'); lightIcon.classList.toggle('hidden');
                if (localStorage.getItem('color-theme')) { if (localStorage.getItem('color-theme') === 'light') { document.documentElement.classList.add('dark'); localStorage.setItem('color-theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('color-theme', 'light'); } } else { if (document.documentElement.classList.contains('dark')) { document.documentElement.classList.remove('dark'); localStorage.setItem('color-theme', 'light'); } else { document.documentElement.classList.add('dark'); localStorage.setItem('color-theme', 'dark'); } }
            });
        </script>
    </body>
</html>