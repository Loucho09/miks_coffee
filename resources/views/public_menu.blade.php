<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Menu - Mik's Coffee Shop</title>
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
                        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                            <span class="text-3xl group-hover:scale-110 transition duration-300">☕</span>
                            <span class="font-bold text-xl md:text-2xl tracking-tight text-stone-900 dark:text-white">MIK'S<span class="text-amber-600">COFFEE</span></span>
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-8 font-medium text-stone-600 dark:text-stone-300">
                        <a href="{{ url('/') }}#hero" class="hover:text-amber-600 transition">Our Coffee</a>
                        <a href="{{ route('menu.index') }}" class="text-amber-600 font-bold transition">Menu</a>
                        <a href="{{ url('/') }}#location" class="hover:text-amber-600 transition">Find Us</a>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a href="{{ route('cart.index') }}" class="text-stone-700 dark:text-stone-200 hover:text-amber-600 font-bold flex items-center gap-2 bg-stone-100 dark:bg-stone-800 px-4 py-2 rounded-full transition">
                                🛒 <span class="hidden sm:inline">Cart</span>
                                @if(session('cart'))
                                    <span class="bg-amber-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ count(session('cart')) }}</span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-stone-500 hover:text-red-500">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="font-bold text-stone-700 dark:text-stone-300 hover:text-amber-600">Sign in</a>
                            <a href="{{ route('register') }}" class="bg-stone-900 dark:bg-white dark:text-stone-900 text-white px-5 py-2.5 rounded-full font-bold hover:bg-stone-700 dark:hover:bg-stone-200 transition">
                                Join Now
                            </a>
                        @endauth
                    </div>

                    <div class="-mr-2 flex items-center md:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-stone-100 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white dark:bg-stone-900 border-b border-stone-200 dark:border-stone-800">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <a href="{{ url('/') }}#hero" class="block py-2 text-stone-600 dark:text-stone-300 font-medium">Our Coffee</a>
                    <a href="{{ route('menu.index') }}" class="block py-2 text-amber-600 font-bold">Menu</a>
                    <a href="{{ url('/') }}#location" class="block py-2 text-stone-600 dark:text-stone-300 font-medium">Find Us</a>
                </div>
                <div class="pt-4 pb-4 border-t border-stone-200 dark:border-stone-800 px-4 space-y-2">
                    @auth
                        <a href="{{ route('cart.index') }}" class="block w-full text-center bg-stone-100 dark:bg-stone-800 text-stone-900 dark:text-white py-2 rounded-lg font-bold">My Cart</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-white py-2 rounded-lg font-bold">Sign In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-amber-600 text-white py-2 rounded-lg font-bold">Join Now</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="bg-stone-900 py-16 text-center border-b border-stone-800">
            <h1 class="text-4xl font-bold text-white mb-4">Our Menu</h1>
            <p class="text-stone-400 text-lg max-w-2xl mx-auto px-4 mb-8">
                Explore our handcrafted beverages and delicious meals.
            </p>

            <form method="GET" action="{{ route('menu.index') }}" class="max-w-xl mx-auto px-4 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search coffee, meals..." 
                       class="w-full px-6 py-4 rounded-full border-none shadow-xl bg-white dark:bg-stone-800 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition text-base">
                <button type="submit" class="absolute right-6 top-2 bottom-2 bg-amber-600 text-white px-6 rounded-full font-bold hover:bg-amber-700 transition">
                    Search
                </button>
            </form>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow">
            
            <div class="flex flex-wrap justify-center gap-3 mb-12 sticky top-24 z-30 py-4 bg-stone-50/95 dark:bg-stone-950/95 backdrop-blur-sm">
                <a href="{{ route('menu.index') }}" 
                   class="px-6 py-2 rounded-full font-bold transition shadow-sm {{ !request('category') ? 'bg-amber-600 text-white' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-700' }}">
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->id]) }}" 
                       class="px-6 py-2 rounded-full font-bold transition shadow-sm {{ request('category') == $category->id ? 'bg-amber-600 text-white' : 'bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden group border border-stone-100 dark:border-stone-800 flex flex-col h-full">
                        
                        <div class="h-56 bg-stone-100 dark:bg-stone-800 overflow-hidden relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-6xl text-stone-300">☕</div>
                            @endif
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-2">
                                <span class="text-xs font-bold text-amber-600 uppercase tracking-wide">
                                    {{ $product->category->name ?? 'Special' }}
                                </span>
                                <h3 class="text-xl font-bold text-stone-900 dark:text-white leading-tight mt-1">
                                    {{ $product->name }}
                                </h3>
                            </div>
                            
                            <p class="text-stone-500 dark:text-stone-400 text-sm mb-4 line-clamp-2 leading-relaxed flex-1">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-stone-100 dark:border-stone-800 mt-auto">
                                <span class="text-xl font-bold text-stone-900 dark:text-white">
                                    ₱{{ number_format($product->price, 2) }}
                                </span>
                                
                                @auth
                                    <a href="{{ route('cart.add', $product->id) }}" class="text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 px-5 py-2.5 rounded-full transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        Add +
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-xs font-bold text-stone-600 dark:text-stone-300 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 px-4 py-2 rounded-full transition">
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="text-center py-24">
                    <span class="text-6xl block mb-4">🔍</span>
                    <p class="text-stone-500 dark:text-stone-400 text-xl font-medium">No items found matching "{{ request('search') }}"</p>
                    <a href="{{ route('menu.index') }}" class="text-amber-600 hover:underline mt-2 inline-block font-bold">View all items</a>
                </div>
            @endif

        </div>

        <footer class="bg-white dark:bg-stone-900 border-t border-stone-200 dark:border-stone-900 py-12 mt-auto text-center">
            <p class="text-stone-400 text-sm">© 2025 Mik's Coffee Shop. All rights reserved.</p>
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