<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ time() }}">

    <title>Menu - Mik's Coffee Shop</title>
    
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
                // 🟢 SOPHISTICATED BEIGE & ESPRESSO THEME (60-30-10 Rule)
                stone: {
                    // LIGHT MODE: The 60% and 30% Beige connection
                    50: '#F5F2EA',  // 60% - Main Beige Background (Soft & Warm)
                    100: '#EBE6D9', // 30% - Secondary/Card Background
                    200: '#DED7C5', // 10% - Subtle Beige Borders
                    
                    // NEUTRAL STEPS: Typography and Accents
                    300: '#D1C8B1', //
                    400: '#B8AD91', //
                    500: '#8F8366', //
                    600: '#736852', //
                    700: '#574F3E', //
                    800: '#3B352A', //
                    
                    // DARK MODE: The "Connected" Charcoal-Beige
                    900: '#1A1816', // 30% - Secondary Dark BG (Warm Charcoal)
                    950: '#0C0B0A', // 60% - Primary Dark BG (Deep Espresso)
                    1000: '#FF0000', // Custom Red Preserved
                },
                
                // 🟢 ACCENT COLORS: 10% Branding
                amber: {
                    400: '#FBBF24', //
                    500: '#F59E0B', // Primary 10% Accent (Brand Orange)
                    600: '#D97706', //
                    700: '#B45309', //
                    1000: '#F59E0B', //
                },

                // 🟢 COFFEE SPECIFIC PALETTE
                'coffee': {
                    100: '#F5E6E0', //
                    600: '#8D5F46', //
                    800: '#4B2C20', //
                    900: '#2C1810', //
                },

                // 🟢 LEGACY BRANDING: Compatibility
                'brand': {
                    orange: '#F59E0B', //
                },

                'dashboard': {
                    1000: '#FF0000', //
                },
            },
            boxShadow: {
                // 🟢 NEW FEATURE: Custom Depth Effects
                // Soft shadow tuned for light beige backgrounds
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)', //
                // Heavy, atmospheric shadow for dark theme depth
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)', //
            },
            letterSpacing: {
                // For that premium high-end look on headers
                'widest': '0.4em',
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
        [x-cloak] { display: none !important; }
        /* Hide scrollbars for the category swiper */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Prevent any horizontal overflow at the root level */
        body, html {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }
    </style>
</head>

<body class="antialiased bg-stone-100  dark:bg-stone-950 text-stone-800 dark:text-stone-200 font-sans flex flex-col min-h-screen">
    
    <nav class="bg-white/90 dark:bg-stone-900/90 backdrop-blur-md sticky top-0 z-50 border-b border-stone-100 dark:border-stone-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600">
                        <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-serif italic text-lg text-stone-900 dark:text-white leading-none">Mik's</span>
                        <span class="font-black text-[8px] uppercase tracking-[0.3em] text-amber-600">Premium Brew</span>
                    </div>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700">
                            <svg class="w-5 h-5 text-stone-600 dark:text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            @if(session('cart'))
                                <span class="absolute -top-1 -right-1 bg-amber-600 text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center shadow-lg">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-700 dark:text-stone-300 hover:text-amber-600 transition">Sign in</a>
                        <a href="{{ route('register') }}" class="bg-amber-600 text-white px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-amber-600/20">Join</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-stone-900 py-12 md:py-24 text-center relative overflow-hidden px-4">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <span class="text-amber-500 font-black uppercase tracking-[0.4em] text-[9px] mb-4 block">The Collection</span>
            <h1 class="text-3xl sm:text-5xl md:text-7xl font-black text-white mb-6 tracking-tight">Our Curated Menu</h1>
            <p class="text-stone-400 text-sm md:text-lg max-w-xl mx-auto font-light leading-relaxed mb-8">Handcrafted experiences for the discerning palate.</p>

            <form method="GET" action="{{ route('menu.index') }}" class="max-w-md mx-auto relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search flavors..." 
                       class="w-full px-6 py-4 rounded-2xl border-none shadow-2xl bg-white dark:bg-stone-800 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all text-sm">
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 flex-grow w-full">
        
        <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-4 no-scrollbar px-1 md:justify-center md:flex-wrap sticky top-20 z-30 bg-stone-100 /80 dark:bg-stone-950/80 backdrop-blur-md transition-colors">
            <a href="{{ route('menu.index') }}" 
               class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-amber-600 text-white shadow-xl' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800' }}">
                All Items
            </a>
            @foreach($categories as $category)
                <a href="{{ route('menu.index', ['category' => $category->id]) }}" 
                   class="whitespace-nowrap px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->id ? 'bg-amber-600 text-white shadow-xl' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-10">
            @foreach($products as $product)
                <div class="group relative flex flex-col h-full bg-white dark:bg-stone-900 rounded-[2rem] border border-stone-100 dark:border-stone-800 transition-all duration-500 hover:border-amber-500/40 overflow-hidden shadow-sm hover:shadow-xl">
                    
                    <div class="relative h-64 overflow-hidden bg-stone-100 dark:bg-stone-800">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-900">
                                <span class="font-serif italic text-[14rem] text-stone-800 dark:text-stone-200 opacity-[0.03] uppercase tracking-tighter select-none z-0 leading-none">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </span>
                            </div>
                            @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-[0.3em] mb-1">
                            {{ $product->category->name ?? 'Special' }}
                        </span>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white tracking-tight mb-2">
                            {{ $product->name }}
                        </h3>
                        <p class="text-stone-500 dark:text-stone-400 text-xs font-light leading-relaxed mb-6 flex-1 line-clamp-2">
                            {{ $product->description }}
                        </p>

                        <div class="flex items-center justify-between pt-6 border-t border-stone-50 dark:border-stone-800 mt-auto">
                            <div class="flex flex-col">
                                @if($product->sizes->count() > 0)
                                    <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest">Starts at</span>
                                    <span class="text-2xl font-black text-stone-900 dark:text-white leading-none">₱{{ number_format($product->sizes->min('price'), 0) }}</span>
                                @else
                                    <span class="text-2xl font-black text-stone-900 dark:text-white leading-none">₱{{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                            
                            @auth
                                <a href="{{ route('cart.add', $product->id) }}" class="p-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-[9px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-500 border border-stone-200 dark:border-stone-800 px-4 py-2 rounded-full transition-colors">Login</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <footer class="bg-white dark:bg-stone-950 border-t border-stone-100 dark:border-stone-900 py-10 mt-auto text-center px-4 transition-colors">
        <p class="text-stone-500 text-[10px] font-black uppercase tracking-[0.4em]">© 2025 Mik's Coffee Shop.</p>
    </footer>

    <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-stone-800 text-stone-500 dark:text-stone-400 shadow-2xl border border-stone-200 dark:border-stone-700 transition-colors">
        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
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