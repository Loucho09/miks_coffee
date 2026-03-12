<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 🟢 PWA Meta Tags - Mobile Compatibility Fix --}}
    <meta name="theme-color" content="#F59E0B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Miks Coffee">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title>{{ config('app.name', "Mik's Coffee Shop - Best Coffee in Trece Martires") }}</title>
    
    <meta name="description" content="Visit Mik's Coffee Shop in Brgy. Osorio, Trece Martires. Premium coffee and savory meals.">
    <link rel="icon" type="image/png" href="{{ asset('/favicon.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('/favicon.png') }}">

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
                fontFamily: { sans: ['Outfit', 'sans-serif'] },
             colors: {
                stone: {
                    50: '#F5F2EA', 100: '#EBE6D9', 200: '#DED7C5', 300: '#D1C8B1', 400: '#B8AD91', 500: '#8F8366', 600: '#736852', 700: '#574F3E', 800: '#3B352A', 900: '#1A1816', 950: '#0C0B0A', 1000: '#FF0000',
                },
                amber: {
                    400: '#FBBF24', 500: '#F59E0B', 600: '#D97706', 700: '#B45309', 1000: '#F59E0B',
                },
                'coffee': {
                    100: '#F5E6E0', 600: '#8D5F46', 800: '#4B2C20', 900: '#2C1810',
                },
                'brand': { orange: '#F59E0B', },
                'dashboard': { 1000: '#FF0000', },
            },
            boxShadow: {
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)',
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)',
            },
            letterSpacing: { 'widest': '0.4em', },
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
<body class="font-sans antialiased bg-stone-100 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 flex flex-col min-h-screen">
    
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

    <main class="flex-grow relative" x-data="{ showLoyaltyCard: false }">
        @auth
            @php
                /* FIX: GLOBAL INITIALIZATION
                   Variables defined at the top of the @auth block for modal accessibility.
                */
                $routeOrderId = request()->route('id');
                $activeOrder = $routeOrderId ? \App\Models\Order::find($routeOrderId) : null;
                $activeToken = $activeOrder->qr_claim_token ?? session('active_claim_token');
                $isClaimed = $activeOrder->points_awarded ?? false;
            @endphp

            @if(Auth::user()->usertype !== 'admin')
                @if(request()->routeIs('checkout.receipt'))
                    <button @click="showLoyaltyCard = true" class="fixed bottom-24 right-6 z-40 p-4 rounded-full bg-amber-500 text-white shadow-2xl hover:scale-110 active:scale-95 transition-all outline-none border-4 border-white dark:border-stone-900 animate-bounce">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-600 border border-white items-center justify-center text-[7px] font-black uppercase">!</span>
                        </span>
                    </button>
                @endif

                <div x-show="showLoyaltyCard" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-full"
                     class="fixed inset-0 z-50 flex items-end justify-center px-4 pb-10 sm:items-center sm:p-0" 
                     style="display: none;">
                    
                    <div class="fixed inset-0 bg-stone-950/60 backdrop-blur-sm" @click="showLoyaltyCard = false"></div>

                    <div class="relative w-full max-w-sm bg-white dark:bg-stone-900 rounded-[2.5rem] shadow-connected overflow-hidden border border-amber-500/20">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black uppercase tracking-widest text-stone-900 dark:text-stone-100">Star ID</h3>
                                        <p class="text-[10px] text-amber-600 font-bold uppercase tracking-widest">Digital Loyalty Card</p>
                                    </div>
                                </div>
                                <button @click="showLoyaltyCard = false" class="text-stone-400 hover:text-stone-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="flex flex-col items-center justify-center p-6 bg-stone-50 dark:bg-stone-950 rounded-[2rem] border-2 border-stone-100 dark:border-stone-800 shadow-inner">
                                @if($activeToken && !$isClaimed)
                                    {!! QrCode::size(180)->backgroundColor(255, 255, 255)->color(120, 113, 108)->margin(1)->generate(route('admin.claim_points', ['token' => $activeToken])) !!}
                                    
                                    <div class="mt-4 p-3 bg-white dark:bg-stone-800 rounded-xl border border-stone-200 dark:border-stone-700 w-full text-center">
                                        <p class="text-[8px] text-stone-400 font-black uppercase mb-1">PC Verification Link:</p>
                                        <a href="{{ route('admin.claim_points', ['token' => $activeToken]) }}" class="text-[10px] text-amber-600 font-mono break-all hover:underline select-all">
                                            {{ route('admin.claim_points', ['token' => $activeToken]) }}
                                        </a>
                                    </div>
                                @elseif($isClaimed)
                                    <div class="text-center py-10">
                                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500 shadow-inner">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <p class="text-[10px] text-green-600 font-black uppercase tracking-widest leading-none">Stars Claimed</p>
                                        <p class="text-[8px] text-stone-400 mt-2 uppercase tracking-tighter italic">Manifest Finalized</p>
                                    </div>
                                @else
                                    <div class="text-center py-10 px-4">
                                        <div class="w-12 h-12 bg-stone-200 dark:bg-stone-800 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <p class="text-[10px] text-stone-400 font-black uppercase tracking-widest leading-relaxed">Manifest unavailable in this sequence</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-8 text-center space-y-4">
                                <div class="inline-block px-4 py-2 bg-amber-500/10 rounded-full border border-amber-500/20">
                                    <span class="text-xs font-black text-amber-600 italic uppercase tracking-widest">#{{ str_pad(auth()->id(), 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <p class="text-[10px] text-stone-400 font-black uppercase tracking-[0.3em] leading-relaxed px-6">Show this code to the barista to earn star points for your order.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

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
                            $adminUser = Auth::user();
                            $isActuallyOnline = $adminUser->is_online && $adminUser->last_seen_at && $adminUser->last_seen_at->diffInMinutes(now()) < 5;
                        @endphp
                        <span class="w-2 h-2 rounded-full {{ $isActuallyOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        <span>Management System {{ $isActuallyOnline ? 'Online' : 'Offline' }}</span>
                        <span class="mx-2 text-stone-200 dark:text-stone-800">|</span>
                        <span>© 2026 </span>
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
                                <input type="email" placeholder="Email" class="w-full px-4 py-2 border border-stone-200 dark:border-stone-700 rounded-xl bg-stone-100 dark:bg-stone-800 text-sm outline-none">
                                <button class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 px-4 py-2 rounded-xl font-bold">Go</button>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-stone-100 dark:border-stone-800 pt-8 text-xs text-stone-400 font-bold uppercase tracking-widest">
                        © Mik's Coffee Shop. Unit 2B, Brgy. Osorio.
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
    
    {{-- 🟢 Service Worker Registration - Fixed for Mobile --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('Miks Coffee PWA Active');
                }).catch(err => {
                    console.error('PWA Registration Fail:', err);
                });
            });
        }
    </script>
    
    @livewireScripts
</body>
</html>