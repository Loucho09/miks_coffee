<nav x-data="{ open: false }" class="bg-white dark:bg-stone-900 border-b border-stone-100 dark:border-stone-800 sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="text-2xl group-hover:scale-110 transition duration-300">☕</span>
                        <span class="font-bold text-xl text-stone-900 dark:text-white hidden md:block tracking-tight">MIK'S<span class="text-amber-600">COFFEE</span></span>
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
                                        ⭐ {{ Auth::user()->points }} Pts
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

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-stone-50 dark:bg-stone-900 border-b border-stone-200 dark:border-stone-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Menu') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rewards.index')" :active="request()->routeIs('rewards.index')">
                {{ __('Rewards') }} ✨
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                {{ __('My Cart') }}
            </x-responsive-nav-link>
            
            @auth
                @if(Auth::user()->usertype === 'admin')
                    <div class="border-t border-stone-200 dark:border-stone-800 my-2"></div>
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.menu.index')" :active="request()->routeIs('admin.menu.*')">{{ __('Manage Menu') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.stock.index')" :active="request()->routeIs('admin.stock.*')">{{ __('Stock') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">{{ __('Customers') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('barista.queue')" :active="request()->routeIs('barista.queue')">{{ __('KDS / Queue') }}</x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-4 border-t border-stone-200 dark:border-stone-800 bg-stone-100 dark:bg-stone-950">
            @auth
                <div class="px-4 mb-3">
                    <div class="font-medium text-base text-stone-800 dark:text-stone-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-stone-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>