@php
    // 🟢 Logic: Check if the customer has points waiting to be claimed
    $hasUnclaimedPoints = false;
    if(Auth::check() && Auth::user()->usertype !== 'admin') {
        $hasUnclaimedPoints = \App\Models\Order::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'ready'])
            ->whereHas('items', function($query) {
                $query->whereDoesntHave('review');
            })->exists();
    }

    // 🟢 Dynamic Tier Calculation - UPDATED TO USE loyalty_points
    $navPoints = Auth::user()->loyalty_points ?? 0;
    $navTier = 'Bronze';
    if($navPoints >= 500) $navTier = 'Gold';
    elseif($navPoints >= 200) $navTier = 'Silver';

    // 🔧 FIX: Admin Online Status with 5-minute threshold
    $adminUser = \App\Models\User::where('email', 'jmloucho09@gmail.com')->first();
    $isAdminOnline = false;
    if ($adminUser && $adminUser->is_online && $adminUser->last_seen_at) {
        $minutesSinceLastSeen = $adminUser->last_seen_at->diffInMinutes(now());
        $isAdminOnline = $minutesSinceLastSeen < 5;
    }

    // 🟢 Unread Support Count logic
    $unreadSupportCount = Auth::check() ? \App\Models\SupportTicket::where('user_id', Auth::id())->where('status', 'replied')->count() : 0;

    // 🟢 Pre-calculate pending tickets for admin badge
    $pendingTickets = (Auth::check() && Auth::user()->usertype === 'admin') ? \App\Models\SupportTicket::where('status', 'pending')->count() : 0;
@endphp

<style>
    [x-cloak] { display: none !important; }
    
    .mobile-nav-scrollbar::-webkit-scrollbar { width: 4px; }
    .mobile-nav-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .mobile-nav-scrollbar::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }
    .nav-blur { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
</style>

<nav x-data="{ open: false }" class="bg-white/80 dark:bg-stone-950/90 backdrop-blur-xl border-b border-stone-200/50 dark:border-stone-800/50 sticky top-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-20 gap-4"> 
            
            <div class="flex items-center flex-1 min-w-0">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-4 group">
                        <div class="relative w-9 h-9 sm:w-12 lg:w-14 sm:h-12 lg:h-14 rounded-full border border-stone-200 dark:border-stone-400 bg-white dark:bg-stone-400 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:rotate-3 group-hover:shadow-connected shadow-sm">
                           <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <h1 class="font-serif italic text-base sm:text-xl lg:text-2xl text-stone-600 dark:text-white leading-none tracking-tight">Mik's</h1>
                            <span class="font-black text-[7px] sm:text-[9px] lg:text-[10px] uppercase tracking-[0.15em] sm:tracking-[0.3em] text-amber-600 leading-none mt-0.5 sm:mt-1">COFFEE</span>
                        </div>
                    </a>
                </div>

                <div class="hidden lg:flex space-x-4 xl:space-x-6 ms-6 xl:ms-10 items-center">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-white font-black uppercase text-[10px] tracking-[0.2em] transition-all relative">
                        {{ __('Menu') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->usertype !== 'admin')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center gap-2 relative">
                                {{ __('Dashboard') }}
                                
                                @if($hasUnclaimedPoints || $unreadSupportCount > 0)
                                    <span class="absolute -top-1 -right-2 flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $unreadSupportCount > 0 ? 'bg-amber-600' : 'bg-amber-500' }} opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $unreadSupportCount > 0 ? 'bg-amber-600' : 'bg-amber-500' }}"></span>
                                    </span>
                                @endif
                            </x-nav-link>
                            <x-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Rewards') }} 
                            </x-nav-link>
                            <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Cart') }}
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="ml-2 bg-stone-900 dark:bg-amber-600 text-white text-[9px] px-2 py-0.5 rounded-full font-black animate-bounce">{{ count(session('cart')) }}</span>
                                @endif
                            </x-nav-link>
                        @endif
                    @endauth

                    <x-nav-link :href="route('support.index')" :active="request()->routeIs('support.*')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                        {{ __('Support') }} 
                        <span class="flex h-1.5 w-1.5 rounded-full {{ $isAdminOnline ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)] animate-pulse' : 'bg-gray-400 shadow-[0_0_4px_rgba(156,163,175,0.3)]' }}"></span>
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->usertype === 'admin')
                            <div class="h-6 w-px bg-stone-200 dark:bg-stone-800 self-center mx-1"></div>
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-stone-900 dark:text-amber-500 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Admin') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Menu') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('Customers') }}
                            </x-nav-link>
                            <x-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em]">
                                {{ __('KDS') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.support.admin_index')" :active="request()->routeIs('admin.support.*')" class="text-stone-500 dark:text-stone-400 hover:text-amber-600 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                                {{ __('Tickets') }} 
                                @if($pendingTickets > 0)
                                    <span class="bg-amber-600 text-white text-[8px] font-black px-1.5 py-0.5 rounded-md leading-none shadow-sm">{{ $pendingTickets }}</span>
                                @endif
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                @auth
                    <div class="hidden lg:flex lg:items-center">
                        <x-dropdown align="right" width="64">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2.5 border border-stone-200 dark:border-stone-800 text-[10px] font-black uppercase tracking-widest rounded-2xl text-stone-700 dark:text-stone-200 bg-white dark:bg-stone-900 hover:bg-stone-50 dark:hover:bg-stone-800 transition shadow-sm group">
                                    <div class="text-left">
                                        <div class="leading-none mb-1 group-hover:text-amber-600 transition-colors">{{ Auth::user()->name }}</div>
                                        <div class="text-[8px] text-amber-600 font-black tracking-tighter opacity-80 uppercase">
                                            {{ Auth::user()->loyalty_points ?? 0 }} PTS • {{ $navTier }}
                                        </div>
                                    </div>
                                    <svg class="ms-3 h-4 w-4 text-stone-400 group-hover:text-amber-600 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-4 py-2 text-[10px] font-black text-stone-400 uppercase tracking-widest border-b border-stone-100 dark:border-stone-800 mb-1">Account</div>
                                <x-dropdown-link :href="Auth::user()->usertype !== 'admin' ? route('dashboard') : route('admin.dashboard')">{{ __('Portal') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Settings') }}</x-dropdown-link>
                                <div class="border-t border-stone-100 dark:border-stone-800 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 font-bold">{{ __('Sign Out') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="flex items-center lg:hidden gap-3">
                        <div class="flex flex-col items-end leading-none shrink-0">
                            <span class="text-[10px] font-black text-amber-600 uppercase">{{ Auth::user()->loyalty_points ?? 0 }} PTS</span>
                            <span class="text-[8px] font-bold text-stone-400 uppercase tracking-tighter">{{ $navTier }}</span>
                        </div>
                        
                        <button @click="open = ! open" class="p-2.5 rounded-xl text-stone-500 bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition shadow-sm relative focus:outline-none active:scale-95">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            @if($hasUnclaimedPoints || $unreadSupportCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $unreadSupportCount > 0 ? 'bg-amber-600' : 'bg-amber-400' }} opacity-75"></span>
                                    <span class="relative h-2.5 w-2.5 rounded-full {{ $unreadSupportCount > 0 ? 'bg-amber-600' : 'bg-amber-400' }}"></span>
                                </span>
                            @endif
                        </button>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-[10px] font-black uppercase text-stone-500 tracking-widest hover:text-amber-600 transition px-2">Login</a>
                    <button @click="open = ! open" class="lg:hidden p-2 text-stone-500"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg></button>
                @endguest
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden bg-white dark:bg-stone-950 border-t border-stone-200 dark:border-stone-800 shadow-2xl max-h-[calc(100vh-64px)] sm:max-h-[calc(100vh-80px)] overflow-y-auto mobile-nav-scrollbar">
        <div class="p-6 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('Menu') }}</x-responsive-nav-link>
            
            @auth
                @if(Auth::user()->usertype !== 'admin')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl font-black uppercase text-[10px] tracking-widest text-amber-600 flex justify-between items-center">
                        {{ __('Dashboard') }}
                        @if($hasUnclaimedPoints || $unreadSupportCount > 0)
                            <span class="px-2 py-0.5 {{ $unreadSupportCount > 0 ? 'bg-amber-600' : 'bg-amber-50' }} {{ $unreadSupportCount > 0 ? 'text-white' : 'text-amber-600' }} rounded-full text-[8px] font-black">
                                {{ $unreadSupportCount > 0 ? $unreadSupportCount . ' NEW' : '+PTS' }}
                            </span>
                        @endif
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('Rewards') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" class="rounded-xl font-black uppercase text-[10px] tracking-widest flex justify-between items-center">
                        {{ __('My Cart') }}
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="bg-stone-900 text-white text-[8px] font-bold px-2 py-0.5 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </x-responsive-nav-link>
                @endif
            @endauth

            <x-responsive-nav-link :href="route('support.index')" :active="request()->routeIs('support.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest flex justify-between items-center">
                {{ __('Support') }}
                <div class="flex items-center gap-2">
                    <span class="text-[8px] text-stone-400 font-black uppercase tracking-tighter">{{ $isAdminOnline ? 'ONLINE' : 'OFFLINE' }}</span>
                    <span class="h-2 w-2 rounded-full {{ $isAdminOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                </div>
            </x-responsive-nav-link>
            
            @auth
                @if(Auth::user()->usertype === 'admin')
                    <div class="h-px bg-stone-100 dark:bg-stone-800 my-4 mx-2"></div>
                    <div class="px-3 mb-2 text-[8px] font-black text-stone-400 uppercase tracking-[0.3em]">Management</div>
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="rounded-xl text-stone-900 dark:text-amber-500 font-black uppercase text-[10px] tracking-widest">{{ __('Admin Panel') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('Menu Editor') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('Customers') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('KDS Queue') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.support.admin_index')" :active="request()->routeIs('admin.support.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest flex justify-between items-center">
                        {{ __('Tickets') }}
                        @if($pendingTickets > 0)
                            <span class="bg-amber-600 text-white text-[8px] font-black px-2 py-0.5 rounded-md">{{ $pendingTickets }} PENDING</span>
                        @endif
                    </x-responsive-nav-link>
                @endif
                
                <div class="h-px bg-stone-100 dark:bg-stone-800 my-4 mx-2"></div>
                <div class="px-3 mb-2 text-[8px] font-black text-stone-400 uppercase tracking-[0.3em]">Account</div>
                
                <div class="flex items-center gap-3 px-4 py-2 mb-2 bg-stone-50 dark:bg-stone-900/50 rounded-2xl mx-1">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-stone-800 dark:text-stone-200 uppercase">{{ Auth::user()->name }}</span>
                        <span class="text-[8px] font-bold text-amber-600 uppercase">{{ Auth::user()->loyalty_points ?? 0 }} PTS • {{ $navTier }}</span>
                    </div>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-black uppercase text-[10px] tracking-widest">{{ __('Settings') }}</x-responsive-nav-link>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" 
                        onclick="event.preventDefault(); this.closest('form').submit();" 
                        class="text-rose-600 font-black uppercase text-[10px] tracking-widest bg-rose-50/30 dark:bg-rose-950/20 mt-3 rounded-xl border border-rose-100 dark:border-rose-900/30">
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            @endauth
        </div>
    </div>
</nav>