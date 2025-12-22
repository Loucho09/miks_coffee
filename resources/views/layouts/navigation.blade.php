<nav x-data="{ open: false }" class="bg-stone-50/90 dark:bg-stone-950/90 backdrop-blur-md border-b border-stone-200/50 dark:border-stone-800/50 sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> 
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                        <div class="relative w-14 h-14 rounded-full border border-stone-200 bg-white flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:border-amber-600 group-hover:shadow-xl shadow-amber-600/10">
                            <img src="{{ asset('favicon.png') }}" 
                                 alt="Mik's Coffee Logo" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:rotate-6"
                                 style="mix-blend-mode: multiply;">
                        </div>
                        
                        <div class="flex flex-col">
                            <h1 class="font-serif italic text-2xl text-stone-900 dark:text-white leading-none tracking-tight">
                                Mik's
                            </h1>
                            <span class="font-black text-[10px] uppercase tracking-[0.3em] text-amber-600">
                                COFFEE
                            </span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 lg:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                        class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors">
                        {{ __('Menu') }}
                    </x-nav-link>

                    <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')"
                        class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors">
                        {{ __('Rewards') }} 
                    </x-nav-link>

                    @auth
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')"
                        class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors">
                        {{ __('My Orders') }} 
                    </x-nav-link>
                    @endauth

                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')"
                        class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors">
                        {{ __('My Cart') }}
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="ml-2 bg-stone-900 dark:bg-amber-600 text-white text-[9px] px-2 py-0.5 rounded-full font-black">{{ count(session('cart')) }}</span>
                        @endif
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->usertype === 'admin')
                            <x-nav-link :href="route('admin.support.admin_index')" :active="request()->routeIs('admin.support.*')"
                                class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors flex items-center gap-2">
                                {{ __('Support') }} 
                                @if(isset($openTicketsCount) && $openTicketsCount > 0)
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[9px] font-black text-white shadow-lg animate-bounce">
                                        {{ $openTicketsCount }}
                                    </span>
                                @endif
                            </x-nav-link>

                            <div class="h-6 w-px bg-stone-300 dark:bg--700 self-center mx-4"></div>
                            
                          <x-nav-link :href="route('admin.dashboard')" 
    :active="request()->routeIs('admin.dashboard')" 
    class="text-stone-1000 dark:text-stone-1000 hover:text-amber-1000 dark:hover:text-amber-1000 font-black uppercase text-[10px] tracking-[0.2em] transition-colors duration-300">
    {{ __('Dashboard') }}
</x-nav-link>
                            
                            <x-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" 
                                class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Menu Mgmt') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" 
                                class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Customers') }}
                            </x-nav-link>

                            <x-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" 
                                class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('KDS') }}
                            </x-nav-link>
                        @else
                             <x-nav-link :href="route('support.index')" :active="request()->routeIs('support.*')"
                                class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-colors">
                                {{ __('Support') }} 
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden lg:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-5 py-2.5 border border-stone-200 dark:border-stone-800 text-[11px] font-black uppercase tracking-widest rounded-2xl text-stone-700 dark:text-stone-200 bg-white dark:bg-stone-900 hover:bg-stone-50 dark:hover:bg-stone-800 transition shadow-sm group">
                                <div class="text-left">
                                    <div class="leading-none mb-1 group-hover:text-amber-600 transition-colors">{{ Auth::user()->name }}</div>
                                    <div class="text-[9px] text-amber-600 font-black tracking-tighter opacity-80">
                                         {{ Auth::user()->points ?? 0 }} PTS
                                    </div>
                                </div>
                                <svg class="ms-3 h-4 w-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-[10px] font-black text-stone-400 uppercase tracking-widest border-b border-stone-100 dark:border-stone-800 mb-1">Account</div>
                            <x-dropdown-link :href="route('orders.index')" class="text-xs font-bold">{{ __('Order History') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')" class="text-xs font-bold">{{ __('Settings') }}</x-dropdown-link>
                            <div class="border-t border-stone-100 dark:border-stone-800 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 font-bold">
                                    {{ __('Sign Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] text-stone-500 dark:text-stone-400 font-black uppercase tracking-[0.2em] hover:text-stone-900 dark:hover:text-white mr-8 transition">Log in</a>
                    <a href="{{ route('register') }}" class="bg-stone-900 dark:bg-amber-600 text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-amber-600 dark:hover:bg-amber-700 transition transform hover:-translate-y-0.5">Join Now</a>
                @endauth
            </div>

            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-2xl text-stone-500 bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-white dark:bg-stone-950 border-t border-stone-200 dark:border-stone-800">
        <div class="pt-4 pb-6 space-y-2 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">
                {{ __('Menu') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">
                {{ __('Rewards') }}
            </x-responsive-nav-link>

            @auth
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">
                {{ __('My Orders') }}
            </x-responsive-nav-link>
            @endauth
            
            @auth
                @if(Auth::user()->usertype === 'admin')
                    <x-responsive-nav-link :href="route('admin.support.admin_index')" :active="request()->routeIs('admin.support.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest flex justify-between items-center">
                        {{ __('Support') }}
                        @if(isset($openTicketsCount) && $openTicketsCount > 0)
                            <span class="bg-rose-600 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-pulse">
                                {{ $openTicketsCount }} NEW
                            </span>
                        @endif
                    </x-responsive-nav-link>

                    <div class="h-px bg-stone-200 dark:bg-stone-800 my-2"></div>
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-amber-600 font-black uppercase text-[10px] tracking-widest">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" class="font-black uppercase text-[10px] tracking-widest">
                        {{ __('Menu Mgmt') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" class="font-black uppercase text-[10px] tracking-widest">
                        {{ __('Customers') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" class="font-black uppercase text-[10px] tracking-widest">
                        {{ __('KDS') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('support.index')" :active="request()->routeIs('support.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">
                        {{ __('Support') }}
                    </x-responsive-nav-link>
                @endif
                
                <div class="h-px bg-stone-200 dark:bg-stone-800 my-2"></div>
                <x-responsive-nav-link :href="route('profile.edit')" class="font-black uppercase text-[10px] tracking-widest">{{ __('Settings') }}</x-responsive-nav-link>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 font-black uppercase text-[10px] tracking-widest">
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            @endauth
        </div>
    </div>
</nav>