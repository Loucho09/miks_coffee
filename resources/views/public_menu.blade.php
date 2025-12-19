<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
                            stone: { 50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1', 400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c', 800: '#292524', 900: '#1c1917', 950: '#0c0a09' },
                            amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
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
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="antialiased bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-200 transition-colors duration-300 font-sans flex flex-col min-h-screen">
        
        <button id="theme-toggle" type="button" class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white/80 dark:bg-stone-800/80 backdrop-blur-md text-stone-500 dark:text-stone-400 shadow-2xl hover:scale-110 transition-all focus:outline-none ring-1 ring-stone-200 dark:ring-stone-700">
            <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <nav x-data="{ open: false }" class="bg-white/90 dark:bg-stone-900/90 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-colors border-b border-stone-100 dark:border-stone-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                            <div class="relative w-12 h-12 rounded-2xl bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600 group-hover:shadow-lg">
                                <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-serif italic text-xl text-stone-900 dark:text-white leading-none">Mik's</span>
                                <span class="font-black text-[10px] uppercase tracking-[0.3em] text-amber-600">Premium Brew</span>
                            </div>
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-8 text-sm font-black uppercase tracking-widest text-stone-500 dark:text-stone-400">
                        <a href="{{ url('/') }}#hero" class="hover:text-amber-600 transition">Our Coffee</a>
                        <a href="{{ route('menu.index') }}" class="text-amber-600 font-black">Menu</a>
                        <a href="{{ url('/') }}#location" class="hover:text-amber-600 transition">Find Us</a>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a href="{{ route('cart.index') }}" class="relative group p-2.5 rounded-xl bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 transition-all hover:border-amber-500/50">
                                <svg class="w-6 h-6 text-stone-600 dark:text-stone-300 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                @if(session('cart'))
                                    <span class="absolute -top-1 -right-1 bg-amber-600 text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center shadow-lg">{{ count(session('cart')) }}</span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs font-black uppercase tracking-widest text-stone-400 hover:text-red-500 transition">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-stone-700 dark:text-stone-300 hover:text-amber-600 transition">Sign in</a>
                            <a href="{{ route('register') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest shadow-lg shadow-amber-600/20 transition transform hover:-translate-y-0.5">Join Now</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div class="bg-stone-900 py-24 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent"></div>
            <div class="relative z-10">
                <span class="text-amber-500 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block">The Collection</span>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight">Our Curated Menu</h1>
                <p class="text-stone-400 text-lg max-w-xl mx-auto px-4 font-light leading-relaxed mb-12">
                    A symphony of premium beans and artisanal ingredients, handcrafted for the discerning palate.
                </p>

                <form method="GET" action="{{ route('menu.index') }}" class="max-w-xl mx-auto px-4 relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search our flavors..." 
                           class="w-full px-8 py-5 rounded-[2rem] border-none shadow-2xl bg-white dark:bg-stone-800 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all text-base placeholder-stone-500 font-light">
                    <button type="submit" class="absolute right-7 top-2.5 bottom-2.5 bg-stone-900 dark:bg-amber-600 text-white px-8 rounded-full font-black text-xs uppercase tracking-widest hover:bg-amber-700 transition shadow-lg">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow">
            <div class="flex flex-wrap justify-center gap-3 mb-20 sticky top-24 z-30 py-4 bg-stone-50/80 dark:bg-stone-950/80 backdrop-blur-md">
                <a href="{{ route('menu.index') }}" 
                   class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300 {{ !request('category') ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->id]) }}" 
                       class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300 {{ request('category') == $category->id ? 'bg-amber-600 text-white shadow-xl shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800 hover:border-amber-500' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12">
                @foreach($products as $product)
                    <div class="group flex flex-col h-full bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-100 dark:border-stone-800 transition-all duration-500 hover:border-amber-500/40 hover:shadow-[0_40px_80px_-20px_rgba(245,158,11,0.15)] overflow-hidden">
                        
                        <div class="relative h-72 bg-stone-100 dark:bg-stone-800 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-200 dark:bg-stone-800">
                                    <svg class="w-16 h-16 text-stone-400 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="mb-4">
                                <span class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em]">
                                    {{ $product->category->name ?? 'House Special' }}
                                </span>
                                <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight mt-1 group-hover:text-amber-500 transition-colors">
                                    {{ $product->name }}
                                </h3>
                            </div>
                            
                            <p class="text-stone-500 dark:text-stone-400 text-sm font-light leading-relaxed mb-8 flex-1 line-clamp-2">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between pt-6 border-t border-stone-50 dark:border-stone-800 mt-auto">
                                <div class="flex flex-col">
                                    @if($product->sizes->count() > 0)
                                        <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest">Starts at</span>
                                        <span class="text-2xl font-black text-stone-900 dark:text-white leading-none">
                                            ₱{{ number_format($product->sizes->min('price'), 0) }}
                                        </span>
                                    @else
                                        <span class="text-2xl font-black text-stone-900 dark:text-white leading-none">
                                            ₱{{ number_format($product->price, 0) }}
                                        </span>
                                    @endif
                                </div>
                                
                                @auth
                                    <a href="{{ route('cart.add', $product->id) }}" class="p-4 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl shadow-lg shadow-amber-600/20 transition-all transform hover:-translate-y-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-500 transition-colors border border-stone-200 dark:border-stone-800 px-6 py-2 rounded-full">
                                        Login to Order
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 text-center">
                    <div class="w-24 h-24 rounded-full bg-stone-100 dark:bg-stone-900 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-stone-400 mb-2">No flavor found</h3>
                    <p class="text-stone-500 mb-8 font-light">Try searching for a different roast or meal.</p>
                    <a href="{{ route('menu.index') }}" class="text-amber-600 font-black uppercase tracking-widest text-[10px] hover:underline">Reset Filters</a>
                </div>
            @endif
        </div>

        <footer class="bg-white dark:bg-stone-950 border-t border-stone-100 dark:border-stone-900 py-16 mt-auto">
           <div class="max-w-7xl mx-auto px-4 text-center">
    <div class="flex items-center justify-center gap-4 mb-8 opacity-40 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-700 ease-in-out">
        <div class="flex gap-6">
            <a href="https://www.facebook.com/share/17aDwarKPW/" 
               target="_blank"
               class="group relative w-12 h-12 rounded-2xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 flex items-center justify-center text-stone-600 dark:text-stone-400 hover:text-[#1877F2] hover:border-[#1877F2]/30 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_10px_20px_-10px_rgba(24,119,242,0.4)]">
                <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <div class="absolute inset-0 rounded-2xl bg-[#1877F2]/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>

            <a href="https://www.instagram.com/mikscoffee?igsh=c3kwb2IxOG80MHg4" 
               target="_blank"
               class="group relative w-12 h-12 rounded-2xl bg-stone-100 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 flex items-center justify-center text-stone-600 dark:text-stone-400 hover:text-[#E4405F] hover:border-[#E4405F]/30 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_10px_20px_-10px_rgba(228,64,95,0.4)]">
                <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
                <div class="absolute inset-0 rounded-2xl bg-[#E4405F]/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
        </div>
    </div>
    
    <p class="text-stone-500 dark:text-stone-500 text-[10px] font-black uppercase tracking-[0.4em]">
        &copy; 2025 Mik's Coffee Shop. Artfully Brewed.
    </p>
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