<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mik's Coffee Shop - Trece Martires</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700&display=swap" rel="stylesheet" />
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
    <body class="antialiased bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 font-sans flex flex-col min-h-screen">
        
        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-stone-800 text-stone-500 dark:text-stone-400 shadow-xl hover:bg-stone-100 dark:hover:bg-stone-700 transition-all focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <nav x-data="{ open: false }" class="bg-white/90 dark:bg-stone-900/90 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-colors border-b border-stone-100 dark:border-stone-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-3xl">☕</span>
                        <span class="font-bold text-xl md:text-2xl tracking-tight text-stone-900 dark:text-white">MIK'S<span class="text-amber-600">COFFEE</span></span>
                    </div>

                    <div class="hidden md:flex space-x-8 font-medium text-stone-600 dark:text-stone-300">
                        <a href="#hero" class="hover:text-amber-600 transition">Our Coffee</a>
                        <a href="{{ route('menu.index') }}" class="hover:text-amber-600 transition">Menu</a>
                        <a href="#location" class="hover:text-amber-600 transition">Find Us</a>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 px-5 py-2.5 rounded-full font-bold hover:shadow-lg transition">
                                    Open App 📱
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="font-bold text-stone-700 dark:text-stone-300 hover:text-amber-600">Sign in</a>
                                <a href="{{ route('register') }}" class="bg-amber-600 text-white px-5 py-2.5 rounded-full font-bold hover:bg-amber-700 hover:shadow-lg transition">
                                    Join Now
                                </a>
                            @endauth
                        @endif
                    </div>

                    <div class="-mr-2 flex items-center md:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-stone-100 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white dark:bg-stone-900 border-b border-stone-200 dark:border-stone-800">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <a href="#hero" class="block py-2 text-stone-600 dark:text-stone-300 font-medium">Our Coffee</a>
                    <a href="{{ route('menu.index') }}" class="block py-2 text-stone-600 dark:text-stone-300 font-medium">Menu</a>
                    <a href="#location" class="block py-2 text-stone-600 dark:text-stone-300 font-medium">Find Us</a>
                </div>
                <div class="pt-4 pb-4 border-t border-stone-200 dark:border-stone-800 px-4 space-y-2">
                    @auth
                        <a href="{{ url('/home') }}" class="block w-full text-center bg-stone-900 text-white py-2 rounded-lg font-bold">Open App</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-white py-2 rounded-lg font-bold">Sign In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-amber-600 text-white py-2 rounded-lg font-bold">Join Now</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div id="hero" class="relative bg-stone-900 overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-b from-stone-900/30 to-stone-900/90"></div>
            </div>
            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center md:text-left md:items-start md:py-40">
                <div class="max-w-2xl">
                    <span class="inline-block py-1 px-4 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs md:text-sm font-bold mb-6 backdrop-blur-sm">
                        📍 NOW OPEN IN TRECE MARTIRES
                    </span>
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        Your Daily Dose of <br>
                        <span class="text-amber-500">Happiness & Coffee</span>
                    </h1>
                    <p class="mt-4 text-lg md:text-xl text-stone-300 mb-10 leading-relaxed font-light">
                        From creamy Spanish Lattes to savory Rice Meals. Experience the cozy vibes at Mik's Coffee Shop right here in Cavite.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <a href="{{ route('menu.index') }}" class="bg-amber-600 text-center text-white text-lg font-bold px-8 py-4 rounded-full shadow-xl hover:bg-amber-700 transition w-full sm:w-auto">
                            View Full Menu
                        </a>
                        <a href="#location" class="bg-white/10 backdrop-blur-md text-center text-white text-lg font-bold px-8 py-4 rounded-full border border-white/20 hover:bg-white/20 transition w-full sm:w-auto">
                            Visit Us
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-16 md:py-24 bg-stone-50 dark:bg-stone-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-stone-900 dark:text-white">Customer Favorites</h2>
                    <p class="mt-2 text-lg text-stone-500 dark:text-stone-400">Taste what everyone is talking about.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featured as $product)
                        <div class="flex flex-col rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 bg-white dark:bg-stone-900 border border-stone-100 dark:border-stone-800 overflow-hidden">
                            <div class="h-56 bg-stone-100 dark:bg-stone-800 overflow-hidden relative group">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl text-stone-300">☕</div>
                                @endif
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-2">{{ $product->name }}</h3>
                                <p class="text-stone-500 dark:text-stone-400 line-clamp-2 mb-6 text-sm">{{ $product->description }}</p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-xl font-bold text-stone-900 dark:text-white">₱{{ number_format($product->price, 0) }}</span>
                                    <a href="{{ route('menu.index') }}" class="text-amber-600 font-bold hover:text-amber-700 flex items-center gap-1 group text-sm">
                                        Order <span class="group-hover:translate-x-1 transition-transform">→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="location" class="bg-stone-900 py-16 md:py-24 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Visit Our Store</h2>
                    <p class="text-lg text-stone-400 mb-8 font-light">
                        We are open every day. Whether you need a morning boost or a late-night hangout spot, Mik's Coffee Shop is the place to be.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-600/20 flex items-center justify-center text-amber-500 text-xl">📍</div>
                            <div>
                                <h4 class="text-lg font-bold text-white">Address</h4>
                                <p class="text-stone-400 text-sm md:text-base">Unit 2B Blk 6 Lot 15 Cavite Avenue<br>Brgy. Osorio, Trece Martires City</p>
                                <p class="text-sm text-amber-500 mt-1">Landmark: Tapat ng High School</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-600/20 flex items-center justify-center text-amber-500 text-xl">⏰</div>
                            <div>
                                <h4 class="text-lg font-bold text-white">Opening Hours</h4>
                                <p class="text-stone-400 text-sm md:text-base">Monday - Sunday: 9:00 AM - 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-1/2 h-80 md:h-96 bg-stone-800 rounded-3xl overflow-hidden border border-stone-700 shadow-2xl relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.4583365512226!2d120.8656874748664!3d14.284781586165046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd81001f3e2407%3A0x7548c28cd57121cc!2sMik%E2%80%99s%20coffee%20shop!5e0!3m2!1sen!2sph!4v1765471789970!5m2!1sen!2sph" 
                        class="absolute inset-0 w-full h-full border-0"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <footer class="bg-white dark:bg-stone-950 border-t border-stone-200 dark:border-stone-900 py-12 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8 text-center sm:text-left">
                    <div>
                        <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">About Us</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 transition text-sm">Our Story</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 transition text-sm">Locations</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Order</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('menu.index') }}" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 transition text-sm">Full Menu</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 transition text-sm">Delivery</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Connect</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-blue-600 transition text-sm">Facebook</a></li>
                            <li><a href="#" class="text-stone-500 dark:text-stone-400 hover:text-pink-600 transition text-sm">Instagram</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-stone-900 dark:text-white tracking-wider uppercase mb-4">Newsletter</h3>
                        <form class="flex gap-2 justify-center sm:justify-start">
                            <input type="email" placeholder="Email" class="w-full px-4 py-2 border border-stone-200 dark:border-stone-700 rounded-lg bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white text-sm">
                            <button class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 text-sm">Go</button>
                        </form>
                    </div>
                </div>
                <div class="border-t border-stone-200 dark:border-stone-800 pt-8 text-center">
                    <p class="text-stone-400 text-sm">© 2025 Mik's Coffee Shop. All rights reserved.</p>
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