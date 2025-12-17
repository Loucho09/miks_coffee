<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
                            stone: { 50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1', 400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c', 800: '#292524', 900: '#1c1917', 950: '#0c0a09' },
                            amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
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
            // Dark Mode Logic
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="antialiased bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 font-sans flex flex-col min-h-screen">
        
        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white/80 dark:bg-stone-800/80 backdrop-blur-md text-stone-500 dark:text-stone-400 shadow-2xl hover:scale-110 transition-transform focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <nav x-data="{ open: false, scrolled: false }" 
             @scroll.window="scrolled = (window.pageYOffset > 20)"
             :class="{'bg-white/90 dark:bg-stone-900/90 backdrop-blur-xl shadow-lg border-b border-stone-200/50 dark:border-stone-800/50': scrolled, 'bg-transparent border-transparent': !scrolled}"
             class="fixed w-full top-0 z-40 transition-all duration-500 ease-in-out">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-2 group cursor-pointer">
                        <span class="text-3xl filter drop-shadow-md group-hover:rotate-12 transition-transform duration-300">☕</span>
                        <span class="font-extrabold text-2xl tracking-tight text-white transition-colors duration-300" 
                              :class="{'text-stone-900 dark:text-white': scrolled}">
                            MIK'S<span class="text-amber-500">COFFEE</span>
                        </span>
                    </div>

                    <div class="hidden md:flex space-x-8 font-bold text-sm uppercase tracking-widest" 
                         :class="{'text-stone-600 dark:text-stone-300': scrolled, 'text-stone-200': !scrolled}">
                        <a href="#hero" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Home</a>
                        <a href="#featured" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Favorites</a>
                        <a href="{{ route('menu.index') }}" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Menu</a>
                        <a href="#location" class="hover:text-amber-500 transition duration-300 relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-amber-500 after:left-0 after:-bottom-1 after:transition-all hover:after:w-full">Visit</a>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="bg-amber-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-amber-700 hover:shadow-lg hover:shadow-amber-600/30 transition-all transform hover:-translate-y-0.5">
                                    My Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="font-bold hover:text-amber-500 transition" :class="{'text-stone-800 dark:text-white': scrolled, 'text-white': !scrolled}">Log in</a>
                                <a href="{{ route('register') }}" class="bg-white text-stone-900 px-6 py-2.5 rounded-full font-bold hover:bg-stone-100 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
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

        <section class="py-24 bg-stone-50 dark:bg-stone-950 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-stone-900 dark:text-white">Why Miks Coffee?</h2>
                    <div class="w-20 h-1 bg-amber-500 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2rem] shadow-xl shadow-stone-200/50 dark:shadow-none border border-stone-100 dark:border-stone-800 hover:border-amber-500/50 transition duration-500 group hover:-translate-y-2">
                        <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition duration-500 group-hover:bg-amber-500 group-hover:text-white">☕</div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-3">Premium Beans</h3>
                        <p class="text-stone-500 dark:text-stone-400 leading-relaxed">Sourced from the finest growers, roasted to perfection to give you that rich, bold flavor every single time.</p>
                    </div>
                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2rem] shadow-xl shadow-stone-200/50 dark:shadow-none border border-stone-100 dark:border-stone-800 hover:border-amber-500/50 transition duration-500 group hover:-translate-y-2">
                        <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition duration-500 group-hover:bg-amber-500 group-hover:text-white">🥐</div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-3">Fresh Pastries</h3>
                        <p class="text-stone-500 dark:text-stone-400 leading-relaxed">Baked daily in-house. Our waffles, donuts, and sandwiches are the perfect companion to your cup.</p>
                    </div>
                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2rem] shadow-xl shadow-stone-200/50 dark:shadow-none border border-stone-100 dark:border-stone-800 hover:border-amber-500/50 transition duration-500 group hover:-translate-y-2">
                        <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition duration-500 group-hover:bg-amber-500 group-hover:text-white">⚡</div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-3">Fast WiFi</h3>
                        <p class="text-stone-500 dark:text-stone-400 leading-relaxed">Stay productive or just chill. High-speed internet is always on the house for all our customers.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="py-24 bg-white dark:bg-[#121212] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-stone-800/10 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <span class="text-amber-600 font-bold tracking-[0.2em] uppercase text-sm">Our Best Sellers</span>
                    <h2 class="text-4xl md:text-5xl font-black text-stone-900 dark:text-white mt-3 mb-6">Taste the Magic</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    @foreach($featured as $product)
                        <div class="group relative">
                            <div class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-stone-100 dark:bg-stone-800 relative shadow-2xl transition-all duration-500 group-hover:shadow-amber-500/20 group-hover:-translate-y-2">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-6xl text-stone-300">☕</div>
                                @endif
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-90 transition"></div>
                                
                                <div class="absolute bottom-0 left-0 p-8 w-full translate-y-2 group-hover:translate-y-0 transition duration-500">
                                    <h3 class="text-2xl font-bold text-white mb-2">{{ $product->name }}</h3>
                                    <p class="text-stone-300 line-clamp-2 mb-6 text-sm opacity-0 group-hover:opacity-100 transition duration-500 delay-100">{{ $product->description }}</p>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-extrabold text-amber-400">₱{{ number_format($product->price, 0) }}</span>
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
                    <a href="{{ route('menu.index') }}" class="inline-block px-8 py-3 rounded-full border-2 border-stone-200 dark:border-stone-800 text-stone-900 dark:text-white font-bold hover:bg-stone-900 hover:border-stone-900 hover:text-white dark:hover:bg-white dark:hover:text-stone-900 transition-all">
                        View Complete Menu
                    </a>
                </div>
            </div>
        </section>

        <section id="location" class="bg-stone-900 text-white py-24 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-16 items-center">
                <div class="w-full md:w-1/2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-stone-800 border border-stone-700 text-xs font-bold uppercase tracking-wider text-stone-400 mb-6">
                        <span>📍</span> Visit Us
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Come for the Coffee,<br>Stay for the <span class="text-amber-500">Vibe.</span></h2>
                    <p class="text-lg text-stone-400 mb-10 font-light leading-relaxed">
                        We are more than just a coffee shop. We are a place to connect, relax, and enjoy the little moments.
                    </p>
                    
                    <div class="space-y-8">
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-stone-800 flex items-center justify-center text-amber-500 text-2xl border border-stone-700">📍</div>
                            <div>
                                <h4 class="text-xl font-bold text-white mb-1">Address</h4>
                                <p class="text-stone-400">Unit 2B Blk 6 Lot 15 Cavite Avenue<br>Brgy. Osorio, Trece Martires City</p>
                                <p class="text-amber-500 text-sm mt-2 font-medium">Landmark: Tapat ng High School</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-stone-800 flex items-center justify-center text-amber-500 text-2xl border border-stone-700">⏰</div>
                            <div>
                                <h4 class="text-xl font-bold text-white mb-1">Opening Hours</h4>
                                <p class="text-stone-400">Daily: 9:00 AM - 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-1/2 h-96 bg-stone-800 rounded-[2.5rem] overflow-hidden border-4 border-stone-800 shadow-2xl relative group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.4583365512226!2d120.8656874748664!3d14.284781586165046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd81001f3e2407%3A0x7548c28cd57121cc!2sMik%E2%80%99s%20coffee%20shop!5e0!3m2!1sen!2sph!4v1765471789970!5m2!1sen!2sph" 
                        class="absolute inset-0 w-full h-full border-0 grayscale group-hover:grayscale-0 transition duration-700"
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </section>

        <footer class="bg-white dark:bg-stone-950 border-t border-stone-200 dark:border-stone-900 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 mb-12 text-center sm:text-left">
                    
                    <div>
                        <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">About Us</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-500 transition font-medium text-sm">Our Story</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-500 transition font-medium text-sm">Locations</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Order</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('menu.index') }}" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-500 transition font-medium text-sm">Full Menu</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-500 transition font-medium text-sm">Delivery</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Connect</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-blue-600 transition font-medium text-sm">Facebook</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-pink-600 transition font-medium text-sm">Instagram</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-stone-900 dark:text-white tracking-[0.2em] uppercase mb-6">Newsletter</h3>
                        <form class="flex gap-2">
                            <input type="email" placeholder="Email" class="w-full px-4 py-2.5 border border-stone-200 dark:border-stone-800 rounded-lg bg-stone-50 dark:bg-stone-900 text-stone-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                            <button class="bg-amber-600 text-white px-5 py-2.5 rounded-lg hover:bg-amber-700 text-sm font-bold shadow-lg shadow-amber-600/20">Go</button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-stone-200 dark:border-stone-900 pt-8 text-center">
                    <p class="text-stone-400 text-sm font-medium">© 2025 Mik's Coffee Shop. All rights reserved.</p>
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