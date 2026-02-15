<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ time() }}">
        <title>Miks Coffee Shop - Trece Martires</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Outfit', 'sans-serif'] },
                        colors: {
                            stone: {
                                50: '#F5F2EA',
                                100: '#EBE6D9',
                                200: '#DED7C5',
                                950: '#0C0B0A',
                                900: '#1A1816',
                                300: '#D1C8B1',
                                400: '#B8AD91',
                                500: '#8F8366',
                                600: '#736852',
                                700: '#574F3E',
                                800: '#3B352A',
                                1000: '#FF0000',
                            },
                            amber: {
                                400: '#FBBF24',
                                500: '#F59E0B',
                                600: '#D97706',
                                700: '#B45309',
                                1000: '#F59E0B',
                            },
                            'coffee': {
                                100: '#F5E6E0',
                                600: '#8D5F46',
                                800: '#4B2C20',
                                900: '#2C1810',
                            },
                            'brand': {
                                orange: '#F59E0B',
                            },
                        },
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
        <style>
            .shadow-connected { box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5); }
            [x-cloak] { display: none !important; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="antialiased bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 font-sans flex flex-col min-h-screen">
        
        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white/80 dark:bg-stone-800/80 backdrop-blur-md text-stone-500 dark:text-stone-400 shadow-2xl hover:scale-110 transition-transform focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <nav x-data="{ open: false, scrolled: false }" 
             @scroll.window="scrolled = (window.pageYOffset > 20)"
             :class="{'bg-white/95 dark:bg-stone-900/95 backdrop-blur-xl shadow-lg border-b border-stone-200 dark:border-stone-800': scrolled, 'bg-transparent border-transparent': !scrolled}"
             class="fixed w-full top-0 z-40 transition-all duration-500 ease-in-out">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-24 items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                            <div class="relative w-14 h-14 rounded-full border-2 border-stone-200 dark:border-stone-700 bg-white flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600 group-hover:shadow-lg">
                                <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <h1 class="font-serif italic text-2xl text-stone-700 dark:text-white leading-none tracking-tight">Mik's</h1>
                                <span class="font-bold text-sm uppercase tracking-[0.25em] text-amber-600 leading-none mt-1">COFFEE</span>
                            </div>
                        </a>
                    </div>

                    <div class="hidden lg:flex space-x-8 font-bold text-xs uppercase tracking-widest" 
                         :class="{'text-stone-800 dark:text-stone-300': scrolled, 'text-stone-900 dark:text-stone-100': !scrolled}">
                        <a href="#hero" class="hover:text-amber-600 transition-colors">Home</a>
                        <a href="#featured" class="hover:text-amber-600 transition-colors">Favorites</a>
                        <a href="{{ route('menu.index') }}" class="hover:text-amber-600 transition-colors">Menu</a>
                        <a href="#location" class="hover:text-amber-600 transition-colors">Visit</a>
                        <a href="{{ route('support.index') }}" class="hover:text-amber-600 transition-colors">Support</a>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden lg:flex items-center gap-4">
                            @auth
                                <a href="{{ url('/home') }}" class="bg-amber-600 text-white px-6 py-2.5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-amber-700 transition-all shadow-md">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest transition px-2" :class="{'text-stone-800 dark:text-stone-300': scrolled, 'text-stone-900 dark:text-stone-100': !scrolled}">Log in</a>
                                <a href="{{ route('register') }}" class="bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 px-6 py-2.5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-amber-600 dark:hover:bg-amber-500 transition-all shadow-md">Sign Up</a>
                            @endauth
                        </div>
                        <button @click="open = ! open" class="lg:hidden p-3 rounded-xl bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" x-cloak class="lg:hidden bg-white dark:bg-stone-900 border-b border-stone-200 dark:border-stone-800 absolute w-full shadow-2xl">
                <div class="pt-4 pb-6 space-y-2 px-6">
                    <a href="#hero" class="block py-3 text-stone-800 dark:text-stone-300 font-bold uppercase text-xs tracking-widest">Home</a>
                    <a href="#featured" class="block py-3 text-stone-800 dark:text-stone-300 font-bold uppercase text-xs tracking-widest">Favorites</a>
                    <a href="{{ route('menu.index') }}" class="block py-3 text-stone-800 dark:text-stone-300 font-bold uppercase text-xs tracking-widest">Full Menu</a>
                    <a href="#location" class="block py-3 text-stone-800 dark:text-stone-300 font-bold uppercase text-xs tracking-widest">Location</a>
                </div>
                <div class="py-6 border-t border-stone-100 dark:border-stone-800 px-6 space-y-4">
                    @auth
                        <a href="{{ url('/home') }}" class="block w-full text-center bg-amber-600 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em]">Open App</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em]">Log In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-stone-900 dark:bg-white text-white dark:text-stone-900 py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em]">Create Account</a>
                    @endauth
                </div>
            </div>
        </nav>

        <section id="hero" class="relative min-h-screen flex items-center bg-stone-100 dark:bg-stone-900 overflow-hidden transition-colors duration-500">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-20 dark:opacity-40 scale-105 animate-[pulse_10s_ease-in-out_infinite]">
                <div class="absolute inset-0 bg-gradient-to-r from-stone-100/95 via-stone-100/70 to-transparent dark:from-black/90 dark:via-black/60 dark:to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-stone-100 via-transparent to-transparent dark:from-stone-900 dark:via-transparent dark:to-transparent"></div>
            </div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-20 text-center md:text-left">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 backdrop-blur-md mb-8">
                        <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span></span>
                        <span class="text-amber-600 dark:text-amber-400 text-sm font-bold tracking-widest uppercase">Now Open in Trece Martires</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-stone-900 dark:text-white leading-tight mb-8 drop-shadow-sm">Brewed for <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-700 dark:from-amber-400 dark:to-amber-600">Greatness.</span></h1>
                    <p class="text-lg md:text-2xl text-stone-700 dark:text-stone-300 mb-10 leading-relaxed font-light max-w-2xl">Experience coffee the way it was meant to be. Premium beans, cozy vibes, and savory meals that make every visit memorable.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('menu.index') }}" class="group relative px-10 py-5 bg-amber-600 rounded-full font-black uppercase text-xs tracking-[0.2em] text-white shadow-xl transition-all hover:scale-105 hover:bg-amber-700 active:scale-95"><span class="relative z-10 flex items-center justify-center gap-2">Order Now <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span></a>
                        <a href="#location" class="px-10 py-5 rounded-full font-black uppercase text-xs tracking-[0.2em] text-stone-800 dark:text-white border border-stone-300 dark:border-white/20 hover:bg-stone-200/50 dark:hover:bg-white/10 backdrop-blur-md transition-all active:scale-95">Visit Us</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="py-32 bg-stone-100 dark:bg-[#0c0b0a] transition-colors duration-500 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span class="text-amber-600 font-black tracking-[0.3em] uppercase text-xs mb-4 block">Top Deals of the Day</span>
                <h2 class="text-4xl md:text-6xl font-black text-stone-900 dark:text-white mb-20 tracking-tighter italic uppercase leading-none">Flavor Stack</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-8 justify-items-center">
                    @for($stack = 0; $stack < 3; $stack++)
                        <div class="relative h-[550px] w-full max-w-[360px] flex items-center justify-center [perspective:1000px] {{ $stack == 2 ? 'hidden lg:flex' : ($stack == 1 ? 'hidden md:flex' : 'flex') }}" 
                             x-data="{ 
                                currentIndex: {{ $stack * 1 }}, 
                                count: {{ $featuredProducts->count() }},
                                next() { this.currentIndex = (this.currentIndex + 1) % this.count },
                                auto() { setInterval(() => this.next(), {{ 3000 + ($stack * 200) }}); }
                             }" x-init="auto()">
                            @foreach($featuredProducts as $index => $product)
                                <div x-show="currentIndex === {{ $index }}" x-cloak
                                     x-transition:enter="transition ease-out duration-600"
                                     x-transition:enter-start="opacity-0 translate-x-32 rotate-12 scale-90"
                                     x-transition:enter-end="opacity-100 translate-x-0 rotate-0 scale-100"
                                     x-transition:leave="transition ease-in duration-600 absolute"
                                     x-transition:leave-start="opacity-100 translate-x-0 rotate-0 scale-100"
                                     x-transition:leave-end="opacity-0 -translate-x-32 -rotate-12 scale-90"
                                     class="absolute w-full z-30">
                                    <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] p-4 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] dark:shadow-[0_50px_100px_-20px_rgba(0,0,0,0.4)] border border-stone-200 dark:border-stone-800">
                                        <div class="relative aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-6 bg-stone-50 dark:bg-stone-800">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                            <div class="absolute bottom-8 left-8 text-left text-white">
                                                <h3 class="text-2xl font-black italic tracking-tighter uppercase mb-1 leading-none">{{ $product->name }}</h3>
                                                <p class="text-amber-500 font-bold text-xl">
                                                    ₱{{ number_format($product->sizes->count() > 0 ? $product->sizes->min('price') : $product->price, 0) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="px-2">
                                            <p class="text-stone-500 dark:text-stone-400 text-sm mb-8 line-clamp-2 italic">"{{ $product->description }}"</p>
                                            <a href="{{ Auth::check() ? route('cart.add', $product->id) : route('login') }}" 
                                               class="flex items-center justify-center w-full py-5 bg-stone-500 dark:bg-amber-600 text-white dark:text-stone-950 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-amber-600 dark:hover:bg-amber-500 transition-all shadow-lg active:scale-95">Grab Deal</a>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="currentIndex === {{ $index }}" class="absolute w-full z-20 translate-x-6 translate-y-4 scale-95 opacity-40 bg-stone-200 dark:bg-stone-800 h-[480px] rounded-[3rem] blur-[1px]"></div>
                                <div x-show="currentIndex === {{ $index }}" class="absolute w-full z-10 translate-x-12 translate-y-8 scale-90 opacity-20 bg-stone-300 dark:bg-stone-700 h-[440px] rounded-[3rem] blur-sm"></div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        <section id="leaderboard" class="py-24 bg-white dark:bg-stone-900/20 relative overflow-hidden border-t border-stone-200 dark:border-stone-800 transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row gap-16 items-center text-center lg:text-left">
                    <div class="w-full lg:w-1/2">
                        <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block">Community Engagement</span>
                        <h2 class="text-4xl md:text-5xl font-black text-stone-900 dark:text-white tracking-tight mb-6 uppercase italic leading-none">Streak Giants</h2>
                        <p class="text-stone-600 dark:text-stone-400 leading-relaxed font-light text-lg mb-8">Join our community of daily regulars. Maintain a consecutive ordering streak to climb the leaderboard and earn exclusive bonus points.</p>
                        <div class="flex items-center justify-center lg:justify-start gap-6">
                            <div class="px-8 py-5 bg-stone-50 dark:bg-stone-900 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm inline-block">
                                <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-1">Active Milestone</p>
                                <p class="text-xl font-bold text-amber-600 tracking-tight">20 PTS Bonus / 3 Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <div class="bg-stone-50 dark:bg-stone-900 dark:bg-stone-900 rounded-[3rem] p-10 text-white shadow-connected border border-stone-800">
                            <div class="flex items-center justify-between mb-12 text-white"><h3 class="text-stone-900 dark:text-white text-xl uppercase tracking-tighter italic">Public Standings</h3><span class="px-4 py-1.5 bg-amber-600 text-stone-950 rounded-full text-[8px] font-black uppercase tracking-widest animate-pulse">Live Feed</span></div>
                            <div class="space-y-6">
                                @forelse($topStreaks as $index => $leader)
                                    <div class="flex items-center justify-between p-5 rounded-2xl bg-stone-950/50 border border-stone-800">
                                        <div class="flex items-center gap-5">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black italic text-sm {{ $index == 0 ? 'bg-amber-600 text-stone-950' : 'bg-stone-800 text-stone-400' }}">{{ $index + 1 }}</div>
                                            <div class="text-left text-white"><p class="font-bold text-sm uppercase tracking-tight">{{ $leader->name }}</p><p class="text-[9px] font-black text-stone-900 dark:text-stone-400 uppercase tracking-widest">{{ $leader->loyalty_tier ?? 'Bronze' }} Member</p></div>
                                        </div>
                                        <div class="text-right text-white"><p class="text-2xl font-black italic text-amber-500 leading-none">{{ $leader->streak_count }}</p><p class="text-[8px] font-black text-stone-500 uppercase tracking-widest">Days</p></div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[10px]">No active streaks recorded.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="location" class="bg-stone-100 dark:bg-stone-900 text-stone-900 dark:text-white py-24 relative overflow-hidden transition-colors duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-16 items-center">
                <div class="space-y-6 flex-1 text-left">
                    <div class="group flex items-start gap-8 p-8 rounded-[3rem] bg-white dark:bg-stone-950 border border-stone-200 dark:border-stone-800 hover:border-amber-500/50 transition-all duration-500 shadow-sm">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-stone-100 dark:bg-gradient-to-br dark:from-stone-800 dark:to-stone-900 flex items-center justify-center border border-stone-200 dark:border-stone-700 shadow-xl"><svg class="w-8 h-8 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                        <div class="flex-1"><h4 class="text-xs font-black uppercase tracking-[0.2em] text-amber-600 dark:text-amber-500 mb-3">Our Main Coffee Shop</h4><h3 class="text-xl font-bold text-stone-900 dark:text-white mb-2 uppercase italic tracking-tighter leading-none">Physical Address</h3><p class="text-stone-600 dark:text-stone-400 font-light leading-relaxed">Unit 2B Blk 6 Lot 15 Cavite Avenue<br>Brgy. Osorio, Trece Martires City</p></div>
                    </div>
                    <div class="group flex items-start gap-8 p-8 rounded-[3rem] bg-white dark:bg-stone-950 border border-stone-200 dark:border-stone-800 hover:border-amber-500/50 transition-all duration-500 shadow-sm">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-stone-100 dark:bg-gradient-to-br dark:from-stone-800 dark:to-stone-900 flex items-center justify-center border border-stone-200 dark:border-stone-700 shadow-xl"><svg class="w-8 h-8 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div class="flex-1"><h4 class="text-xs font-black uppercase tracking-[0.2em] text-amber-600 dark:text-amber-500 mb-3">Service Hours</h4><h3 class="text-xl font-bold text-stone-900 dark:text-white mb-2 uppercase italic tracking-tighter leading-none">We are Open</h3><div class="flex items-baseline gap-2 text-stone-600 dark:text-stone-400 font-light"><span class="text-stone-900 dark:text-white font-bold tracking-tight">Monday — Sunday: 9:00 AM — 10:00 PM</span></div></div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 h-96 bg-stone-200 dark:bg-stone-800 rounded-[3rem] overflow-hidden border-4 border-stone-200 dark:border-stone-800 shadow-connected relative"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14700.4997719728!2d120.85845597612617!3d14.28626677775182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd81001f3e2407%3A0x7548c28cd57121cc!2sMik%E2%80%99s%20coffee%20shop!5e1!3m2!1sen!2sus!4v1770974579948!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
            </div>
        </section>

         <footer class="bg-stone-200 dark:bg-stone-950 border-t border-stone-200 dark:border-stone-900 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
            
            <div class="md:col-span-4 flex flex-col items-center md:items-start">
                <a href="{{ route('home') }}" class="flex items-center gap-4 group mb-6">
                    <div class="relative w-12 h-12 rounded-2xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:border-amber-500 group-hover:shadow-[0_0_20px_-5px_rgba(245,158,11,0.3)]">
                        <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="flex flex-col"> 
                        <span class="font-serif italic text-xl text-stone-900 dark:text-white leading-none">Mik's</span>
                        <span class="font-black text-[10px] uppercase tracking-[0.3em] text-amber-600">Premium Brew</span>
                    </div>
                </a>
                <p class="text-stone-500 dark:text-stone-400 text-sm leading-relaxed max-w-xs text-center md:text-left font-light">
                    Crafting extraordinary coffee experiences in the heart of Trece Martires. Every bean tells a story of quality and passion.
                </p>
            </div>    
   
            <div class="md:col-span-2 text-center md:text-left">
                <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 tracking-[0.4em] uppercase mb-8">Discover</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="group flex items-center justify-center md:justify-start gap-2 text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-white transition-all duration-300">
                        <span class="w-0 group-hover:w-4 h-px bg-amber-500 transition-all duration-300"></span>
                        <span class="text-sm font-medium group-hover:translate-x-1 transition-transform">Our Story</span>
                    </a></li>
                    <li><a href="#location" class="group flex items-center justify-center md:justify-start gap-2 text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-white transition-all duration-300">
                        <span class="w-0 group-hover:w-4 h-px bg-amber-500 transition-all duration-300"></span>
                        <span class="text-sm font-medium group-hover:translate-x-1 transition-transform">Locations</span>
                    </a></li>
                </ul>
            </div>

            <div class="md:col-span-2 text-center md:text-left">
                <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 tracking-[0.4em] uppercase mb-8">Experience</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('menu.index') }}" class="group flex items-center justify-center md:justify-start gap-2 text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-white transition-all duration-300">
                        <span class="w-0 group-hover:w-4 h-px bg-amber-500 transition-all duration-300"></span>
                        <span class="text-sm font-medium group-hover:translate-x-1 transition-transform">Full Menu</span>
                    </a></li>
                    <li><a href="#" class="group flex items-center justify-center md:justify-start gap-2 text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-white transition-all duration-300">
                        <span class="w-0 group-hover:w-4 h-px bg-amber-500 transition-all duration-300"></span>
                        <span class="text-sm font-medium group-hover:translate-x-1 transition-transform">Delivery</span>
                    </a></li>
                </ul>
            </div>

            <div class="md:col-span-4 flex flex-col items-center md:items-start">
                <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-500 tracking-[0.4em] uppercase mb-8 text-center md:text-left">Join the Culture</h3>
                <form class="flex w-full p-1.5 rounded-2xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 focus-within:border-amber-500/50 transition-all duration-500 mb-8">
                    <input type="email" placeholder="Your email address" class="w-full px-4 bg-transparent border-none text-stone-900 dark:text-white text-sm focus:ring-0 placeholder-stone-500 font-light">
                    <button class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-amber-600/20">Join</button>
                </form>

                <div class="flex gap-4">
                    <a href="https://www.facebook.com/share/17aDwarKPW/" class="w-10 h-10 rounded-xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 flex items-center justify-center text-stone-600 dark:text-stone-400 hover:text-blue-600 hover:border-blue-600/30 transition-all duration-300 group">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <a href="https://www.instagram.com/mikscoffee?igsh=c3kwb2IxOG80MHg4" class="w-10 h-10 rounded-xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 flex items-center justify-center text-stone-600 dark:text-stone-400 hover:text-pink-600 hover:border-pink-600/30 transition-all duration-300 group">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>

            </div>
        </div>

        <div class="border-t border-stone-200 dark:border-stone-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-stone-500 dark:text-stone-500 text-[10px] font-black uppercase tracking-[0.2em]">
                © 2025 Mik's Coffee Shop. Artfully Designed.
            </p>
            <!-- <div class="flex gap-8">
                <a href="#" class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-amber-500 transition-colors">Privacy</a>
                <a href="#" class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-amber-500 transition-colors">Terms</a>
            </div> -->
        </div>
    </div>
</footer>
                    
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