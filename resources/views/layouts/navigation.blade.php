<nav x-data="{ open: false }" class="bg-white dark:bg-stone-900 border-b border-stone-100 dark:border-stone-800 sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
               <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                        
                        <div class="relative w-14 h-14 rounded-full border-2 border-stone-200 dark:border-stone-700 bg-white flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600 group-hover:shadow-lg">
                            <img src="{{ asset('favicon.png') }}" 
                                 alt="Mik's Coffee Logo" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        
                        <div class="flex flex-col">
                            <h1 class="font-serif italic text-2xl text-stone-900 dark:text-white leading-none tracking-tight">
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

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 lg:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 dark:hover:text-amber-400 font-medium">
                        {{ __('Menu') }}
                    </x-nav-link>

                    <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')"
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 dark:hover:text-amber-400 font-medium">
                        {{ __('Rewards') }} 
                    </x-nav-link>

                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')"
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 dark:hover:text-amber-400 font-medium">
                        {{ __('My Cart') }}
                        @if(session('cart'))
                            <span class="ml-2 bg-amber-600 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ count(session('cart')) }}</span>
                        @endif
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->usertype === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-amber-700 dark:text-amber-500 font-bold">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600">
                                {{ __('Menu') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.stock.index')" :active="request()->routeIs('admin.stock.*')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600">
                                {{ __('Stock') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600">
                                {{ __('Customers') }}
                            </x-nav-link>
                            <x-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600">
                                {{ __('KDS') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden lg:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-bold rounded-full text-stone-700 dark:text-stone-200 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 focus:outline-none transition">
                                <div class="text-left">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-amber-600 dark:text-amber-400 font-normal">
                                         {{ Auth::user()->points }} Pts
                                    </div>
                                </div>
                                <div class="ms-2">▼</div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('orders.index')">{{ __('My Orders') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-stone-600 dark:text-stone-300 font-bold hover:text-amber-600 mr-4">Log in</a>
                    <a href="{{ route('register') }}" class="bg-stone-900 dark:bg-white text-white dark:text-stone-900 px-4 py-2 rounded-full text-sm font-bold hover:shadow-lg transition">Join Now</a>
                @endauth
            </div>

            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-stone-100 dark:hover:bg-stone-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>