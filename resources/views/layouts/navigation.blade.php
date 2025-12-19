<nav x-data="{ open: false }" class="bg-white dark:bg-stone-900 border-b border-stone-100 dark:border-stone-800 sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> 
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                        <div class="relative w-12 h-12 rounded-full border-2 border-stone-200 dark:border-stone-700 bg-white flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-amber-600 group-hover:shadow-lg">
                            <img src="{{ asset('favicon.png') }}" 
                                 alt="Mik's Coffee Logo" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        
                        <div class="flex flex-col">
                            <h1 class="font-serif italic text-xl text-stone-900 dark:text-white leading-none tracking-tight">
                                Mik's
                            </h1>
                            <span class="font-bold text-[10px] uppercase tracking-[0.25em] text-amber-600">
                                COFFEE
                            </span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 lg:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
                        {{ __('Menu') }}
                    </x-nav-link>

                    <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')"
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
                        {{ __('Rewards') }} 
                    </x-nav-link>

                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')"
                        class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
                        {{ __('My Cart') }}
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="ml-2 bg-amber-600 text-white text-[10px] px-2 py-0.5 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->usertype === 'admin')
                            <div class="h-8 w-px bg-amber-500/40 dark:bg-amber-600/30 self-center mx-4"></div>
                            
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-amber-600 font-black uppercase text-xs tracking-widest">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            
                            <x-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
                                {{ __('Menu Mgmt') }}
                            </x-nav-link>

                             <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.index')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
                                {{ __('Costumer') }}
                            </x-nav-link>
                            
                            <x-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" class="text-stone-600 dark:text-stone-300 hover:text-amber-600 font-bold uppercase text-xs tracking-widest">
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
                            <button class="inline-flex items-center px-4 py-2 border border-stone-200 dark:border-stone-700 text-sm leading-4 font-bold rounded-full text-stone-700 dark:text-stone-200 bg-white dark:bg-stone-800 hover:bg-stone-50 dark:hover:bg-stone-700 transition shadow-sm">
                                <div class="text-left">
                                    <div class="leading-tight">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-amber-600 dark:text-amber-400 font-black uppercase tracking-tighter">
                                         {{ Auth::user()->points ?? 0 }} Points
                                    </div>
                                </div>
                                <svg class="ms-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('orders.index')">{{ __('My Orders') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-dropdown-link>
                            <hr class="border-stone-100 dark:border-stone-800">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                                    {{ __('Sign Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-stone-600 dark:text-stone-300 font-black uppercase tracking-widest hover:text-amber-600 mr-6 transition">Log in</a>
                    <a href="{{ route('register') }}" class="bg-amber-600 text-white px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest hover:bg-amber-700 hover:shadow-lg hover:shadow-amber-600/30 transition transform hover:-translate-y-0.5">Join Now</a>
                @endauth
            </div>

            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white dark:bg-stone-900 border-t border-stone-100 dark:border-stone-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Menu') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')">
                {{ __('Rewards') }}
            </x-responsive-nav-link>
        </div>
        
        @auth
        <div class="pt-4 pb-1 border-t border-stone-200 dark:border-stone-800">
            <div class="px-4 flex justify-between items-center">
                <div>
                    <div class="font-bold text-base text-stone-800 dark:text-stone-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-amber-600">{{ Auth::user()->points }} Points</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('orders.index')">{{ __('My Orders') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>