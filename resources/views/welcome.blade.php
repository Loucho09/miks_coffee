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
                'dashboard': {
                    1000: '#FF0000',
                },
            },
                        animation: {
                            'float': 'float 6s ease-in-out infinite',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-20px)' },
                            }
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
        <style>
            .shadow-connected { box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5); }
        </style>
    </head>
    <body class="antialiased bg-stone-100 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 font-sans flex flex-col min-h-screen">
        
        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white/80 dark:bg-stone-800/80 backdrop-blur-md text-stone-500 dark:text-stone-400 shadow-2xl hover:scale-110 transition-transform focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <nav x-data="{ open: false, scrolled: false }" 
             @scroll.window="scrolled = (window.pageYOffset > 20)"
             :class="{'bg-stone-100 dark:bg-stone-900/90 backdrop-blur-xl shadow-lg border-b border-stone-200/50 dark:border-stone-800/50': scrolled, 'bg-transparent border-transparent': !scrolled}"
             class="fixed w-full top-0 z-40 transition-all duration-500 ease-in-out">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                        
                        <div class="relative w-14 h-14 rounded-full border-2 border-stone-200 dark:border-stone-700 bg-white flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600 group-hover:shadow-lg">
                            <img src="{{ asset('favicon.png') }}" 
                                 alt="Mik's Coffee Logo" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        
                        <div class="flex flex-col">
                            <h1 class="font-serif italic text-2xl text-stone-600 dark:text-white leading-none tracking-tight">
                                Mik's
                            </h1>
                            <span class="font-bold text-sm uppercase tracking-[0.25em] text-amber-600">
                                COFFEE
                            </span>
                            <span class="text-[9px] uppercase tracking-[0.3em] text-stone-400 font-bold -mt-0.5">
                                PREMIUM BREW
                            </span>
                        </div>
                    </a>
                </div>

                    <div class="hidden md:flex space-x-8 font-bold text-sm uppercase tracking-widest" 
                         :class="{'text-stone-600 dark:text-stone-300': scrolled, 'text-stone-200': !scrolled}">
                        <a href="#hero" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Home</a>
                        <a href="#featured" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Favorites</a>
                        <a href="{{ route('menu.index') }}" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Menu</a>
                        <a href="#location" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Visit</a>
                        <a href="{{ route('support.index') }}" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Support</a>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="bg-amber-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-amber-700 hover:shadow-lg hover:shadow-amber-600/30 transition-all transform hover:-translate-y-0.5">
                                    My Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="font-bold hover:text-amber-500 transition" :class="{'text-stone-800 dark:text-white': scrolled, 'text-white': !scrolled}">Log in</a>
                                <a href="{{ route('register') }}" class="bg-stone-100 text-stone-900 px-6 py-2.5 rounded-full font-bold hover:bg-amber-500 hover:text100 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    Sign Up
                                </a>
                            @endauth
                        @endif
                    </div>

                    <div class="-mr-2 flex items-center md:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition text-stone-400 hover:text-white">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" class="md:hidden bg-stone-900/95 backdrop-blur-xl border-b border-stone-800 absolute w-full shadow-2xl">
                <div class="pt-4 pb-6 space-y-2 px-4">
                    <a href="#hero" class="block px-4 py-3 rounded-lg text-stone-300 font-medium hover:text-white hover:bg-stone-800 transition">Home</a>
                    <a href="{{ route('menu.index') }}" class="block px-4 py-3 rounded-lg text-stone-300 font-medium hover:text-white hover:bg-stone-800 transition">Full Menu</a>
                    <a href="#location" class="block px-4 py-3 rounded-lg text-stone-300 font-medium hover:text-white hover:bg-stone-800 transition">Location</a>
                    <a href="{{ route('support.index') }}" class="block px-4 py-3 rounded-lg text-stone-300 font-medium hover:text-white hover:bg-stone-800 transition">Support</a>
                </div>
                <div class="py-6 border-t border-stone-800 px-4 space-y-3">
                    @auth
                        <a href="{{ url('/home') }}" class="block w-full text-center bg-amber-600 text-white py-3 rounded-xl font-bold hover:bg-amber-700">Open App</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-stone-800 text-white py-3 rounded-xl font-bold hover:bg-stone-700">Log In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white text-stone-900 py-3 rounded-xl font-bold hover:bg-stone-200">Create Account</a>
                    @endauth
                </div>
            </div>
        </nav>

        <section id="hero" class="relative min-h-screen flex items-center bg-stone-900 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-40 scale-105 animate-[pulse_10s_ease-in-out_infinite]">
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-transparent to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-20">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 backdrop-blur-md mb-8 animate-fade-in-up">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-amber-400 text-sm font-bold tracking-widest uppercase">Now Open in Trece Martires</span>
                    </div>

                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-tight mb-8 drop-shadow-2xl">
                        Brewed for <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">Greatness.</span>
                    </h1>

                    <p class="text-lg md:text-2xl text-stone-300 mb-10 leading-relaxed font-light max-w-2xl drop-shadow-lg">
                        Experience coffee the way it was meant to be. Premium beans, cozy vibes, and savory meals that make every visit memorable.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('menu.index') }}" class="group relative px-8 py-4 bg-amber-600 rounded-full font-bold text-white text-lg overflow-hidden shadow-[0_0_40px_-10px_rgba(245,158,11,0.5)] transition-all hover:scale-105 hover:bg-amber-500">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Order Now 
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </span>
                        </a>
                        <a href="#location" class="px-8 py-4 rounded-full font-bold text-white border border-white/20 hover:bg-white/10 backdrop-blur-md transition text-lg text-center hover:border-white/40">
                            Visit Us
                        </a>
                    </div>
                </div>
            </div>
        </section>

      <section class="py-32 bg-white dark:bg-stone-950 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[120px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-24">
            <span class="text-amber-500 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block">The Craftsmanship</span>
            <h2 class="text-4xl md:text-6xl font-black text-stone-900 dark:text-white tracking-tight">Why Mik's Coffee?</h2>
            <div class="mt-8 flex justify-center gap-1.5">
                <div class="w-16 h-1 bg-amber-50 rounded-full"></div>
                <div class="w-2 h-1 bg-amber-500/20 rounded-full"></div>
                <div class="w-2 h-1 bg-amber-500/10 rounded-full"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="group relative p-10 rounded-[3rem] bg-stone-100 dark:bg-stone-900/40 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-3 hover:border-amber-500/50 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.15)]">
                <div class="relative z-10">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500/10 flex items-center justify-center mb-8 border border-amber-500/20 group-hover:bg-amber-500 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-inner">
                        <svg class="w-10 h-10 text-amber-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-4 tracking-tight">Premium Beans</h3>
                    <p class="text-stone-500 dark:text-stone-400 leading-relaxed font-light">Sourced from the world’s most renowned high-altitude estates, roasted in micro-batches for an unparalleled flavor profile.</p>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 via-amber-500/0 to-amber-500/[0.03] rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <div class="group relative p-10 rounded-[3rem] bg-stone-100 dark:bg-stone-900/40 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-3 hover:border-amber-500/50 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.15)]">
                <div class="relative z-10">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500/10 flex items-center justify-center mb-8 border border-amber-500/20 group-hover:bg-amber-500 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500 shadow-inner">
                        <svg class="w-10 h-10 text-amber-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.703 2.703 0 00-3 0 2.704 2.704 0 01-3 0 2.703 2.703 0 00-3 0 2.704 2.704 0 01-1.5-.454M3 20h18M4 11a4 4 0 118 0v1H4v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-4 tracking-tight">Handcrafted Eats</h3>
                    <p class="text-stone-500 dark:text-stone-400 leading-relaxed font-light">Every pastry is rolled, proofed, and baked at sunrise in our kitchen to ensure a crisp, golden exterior and airy crumb.</p>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 via-amber-500/0 to-amber-500/[0.03] rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <div class="group relative p-10 rounded-[3rem] bg-stone-100 dark:bg-stone-900/40 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-3 hover:border-amber-500/50 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.15)]">
                <div class="relative z-10">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500/10 flex items-center justify-center mb-8 border border-amber-500/20 group-hover:bg-amber-500 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-inner">
                        <svg class="w-10 h-10 text-amber-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-900 dark:text-white mb-4 tracking-tight">Ultra-Fast Fiber</h3>
                    <p class="text-stone-500 dark:text-stone-400 leading-relaxed font-light">A sanctuary for productivity. Enjoy seamless gigabit connectivity while you sip, perfect for the modern nomad.</p>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 via-amber-500/0 to-amber-500/[0.03] rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
        </div>
    </div>
</section>

        <section id="leaderboard" class="py-24 bg-stone-50 dark:bg-stone-900/20 relative overflow-hidden border-t border-stone-200 dark:border-stone-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row gap-16 items-center">
                    <div class="w-full lg:w-1/2">
                        <span class="text-amber-500 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block">Community Engagement</span>
                        <h2 class="text-4xl md:text-5xl font-black text-stone-900 dark:text-white tracking-tight mb-6 uppercase italic leading-none">Streak Giants</h2>
                        <p class="text-stone-500 dark:text-stone-400 leading-relaxed font-light text-lg mb-8">
                            Join our community of daily regulars. Maintain a consecutive ordering streak to climb the leaderboard and earn exclusive bonus points. Every milestone reached brings unique rewards to your dashboard.
                        </p>
                        <div class="flex items-center gap-6">
                            <div class="px-6 py-4 bg-white dark:bg-stone-900 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                                <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-1">Active Milestone</p>
                                <p class="text-lg font-bold text-amber-600 tracking-tight">20 PTS Bonus / 3 Days</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-1/2">
                        <div class="bg-stone-900 rounded-[3rem] p-10 text-white shadow-connected border border-stone-800">
                            <div class="flex items-center justify-between mb-10">
                                <h3 class="font-black text-xl uppercase tracking-tighter italic">Public Standings</h3>
                                <span class="px-3 py-1 bg-amber-600 text-stone-950 rounded-full text-[8px] font-black uppercase tracking-widest">Live</span>
                            </div>

                            <div class="space-y-6">
                                @forelse($topStreaks as $index => $leader)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-stone-950/50 border border-stone-800">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black italic text-xs {{ $index == 0 ? 'bg-amber-600 text-stone-950' : 'bg-stone-800 text-stone-400' }}">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm uppercase tracking-tight text-white">{{ $leader->name }}</p>
                                                <p class="text-[9px] font-black text-stone-500 uppercase tracking-widest">
                                                    {{ $leader->loyalty_tier ?? 'Bronze' }} Member
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-black italic text-amber-500 leading-none">{{ $leader->streak_count }}</p>
                                            <p class="text-[8px] font-black text-stone-500 uppercase tracking-widest">Day Streak</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[10px]">
                                        No active streaks recorded.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="py-24 bg-stone-100 dark:bg-[#121212] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-amber-600 font-bold tracking-[0.2em] uppercase text-sm">Our Best Sellers</span>
            <h2 class="text-4xl md:text-5xl font-black text-stone-900 dark:text-white mt-3 mb-6">Taste the Magic</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($featuredProducts as $product)
                <div class="group relative">
                    <div class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-stone-100 dark:bg-stone-800 relative shadow-2xl transition-all duration-500 hover:shadow-amber-500/20 hover:-translate-y-2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-900">
                                <span class="font-serif italic text-[14rem] text-stone-800 dark:text-stone-200 opacity-[0.03] uppercase tracking-tighter select-none leading-none">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-90 transition"></div>
                        
                        <div class="absolute bottom-0 left-0 p-8 w-full translate-y-2 group-hover:translate-y-0 transition duration-500">
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $product->name }}</h3>
                            
                            <p class="text-stone-300 line-clamp-2 mb-6 text-sm opacity-0 group-hover:opacity-100 transition duration-500 delay-100">
                                {{ $product->description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    @if($product->sizes->count() > 0)
                                        <span class="text-[10px] text-amber-500 uppercase font-black tracking-widest mb-0.5">Starts at</span>
                                        <span class="text-2xl font-extrabold text-amber-400 leading-none">₱{{ number_format($product->sizes->min('price'), 0) }}</span>
                                    @else
                                        <span class="text-2xl font-extrabold text-amber-400">₱{{ number_format($product->price, 0) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('cart.add', $product->id) }}" class="bg-white text-stone-900 p-3 rounded-full hover:bg-amber-500 hover:text-white transition shadow-lg hover:rotate-90 duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('menu.index') }}" class="inline-block px-8 py-3 rounded-full border-2 border-stone-200 dark:border-stone-800 text-stone-900 dark:text-white font-bold hover:bg-stone-900 hover:text-white transition-all">
                View Complete Menu
            </a>
        </div>
    </div>
</section>

        <section id="location" class="bg-stone-900 text-white py-24 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-16 items-center">

                <div class="space-y-6">
    <div class="group flex items-start gap-6 p-6 rounded-[2rem] bg-stone-900/40 backdrop-blur-md border border-stone-800 hover:border-amber-500/50 transition-all duration-500 hover:shadow-[0_0_40px_-15px_rgba(245,158,11,0.2)]">
        <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-stone-800 to-stone-900 flex items-center justify-center border border-stone-700 group-hover:border-amber-500/50 transition-colors duration-500">
            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-amber-500 mb-2">Our Main Coffee Shop</h4>
            <h3 class="text-xl font-bold text-white mb-1">Physical Address</h3>
            <p class="text-stone-400 font-light leading-relaxed mb-3">
                Unit 2B Blk 6 Lot 15 Cavite Avenue<br>
                Brgy. Osorio, Trece Martires City
            </p>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-[10px] font-black uppercase tracking-widest text-amber-400">
                 Landmark: Tapat ng High School
            </div>
        </div>
    </div>

    <div class="group flex items-start gap-6 p-6 rounded-[2rem] bg-stone-900/40 backdrop-blur-md border border-stone-800 hover:border-amber-500/50 transition-all duration-500 hover:shadow-[0_0_40px_-15px_rgba(245,158,11,0.2)]">
        <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-stone-800 to-stone-900 flex items-center justify-center border border-stone-700 group-hover:border-amber-500/50 transition-colors duration-500">
            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-amber-500 mb-2">Service Hours</h4>
            <h3 class="text-xl font-bold text-white mb-1">We are Open</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-stone-400 font-light">Monday — Sunday:</span>
                <span class="text-white font-bold tracking-tight">9:00 AM — 10:00 PM</span>
            </div>
        </div>
    </div>
</div>
                
                <div class="w-full md:w-1/2 h-96 bg-stone-800 rounded-[2.5rem] overflow-hidden border-4 border-stone-800 shadow-2xl relative group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15444.606!2d120.9161!3d14.2811!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd7f0000000000%3A0x0!2zMTTCsDE2JzUyLjAiTiAxMjDCsDU0JzU4LjAiRQ!5e0!3m2!1sen!2sph!4v1700000000000" 
                        class="absolute inset-0 w-full h-full border-0 grayscale group-hover:grayscale-0 transition duration-700"
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </section>

      <footer class="bg-stone-100 dark:bg-stone-950 border-t border-stone-200 dark:border-stone-900 pt-24 pb-12">
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
            <div class="flex gap-8">
                <a href="#" class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-amber-500 transition-colors">Privacy</a>
                <a href="#" class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-amber-500 transition-colors">Terms</a>
            </div>
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