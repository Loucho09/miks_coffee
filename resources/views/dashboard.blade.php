<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 flex items-center p-4 text-amber-800 border-l-4 border-amber-500 bg-amber-50 dark:bg-stone-800 dark:text-amber-500 rounded-r-xl shadow-sm transition-all">
                    <div class="flex-shrink-0 text-xl mr-3">🎉</div>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                </div>
            @endif

            <div class="mb-10">
                <h2 class="font-serif italic text-4xl md:text-5xl text-stone-900 dark:text-white leading-tight">
                    @php
                        $hour = date('H');
                        $greeting = $hour < 12 ? 'Morning' : ($hour < 17 ? 'Afternoon' : 'Evening');
                    @endphp
                    Good {{ $greeting }}, {{ Auth::user()->name }}!
                </h2>
                <p class="text-stone-500 dark:text-stone-400 mt-2 text-lg">Your daily dose of greatness is just a tap away.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-8">
                    
                    <div class="relative overflow-hidden bg-stone-900 rounded-[2rem] p-8 text-white shadow-2xl group transition-all duration-500 hover:shadow-amber-900/20">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-600/20 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-amber-600/40 transition-colors"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-12">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.3em] text-stone-400 font-bold mb-1">MEMBER STATUS</p>
                                    <p class="text-amber-500 font-bold uppercase tracking-widest text-sm">Gold Tier</p>
                                </div>
                                <div class="w-10 h-10 border border-stone-700 rounded-xl flex items-center justify-center">
                                    <img src="{{ asset('favicon.png') }}" class="w-6 h-6 grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500" alt="Logo">
                                </div>
                            </div>

                            <div class="mb-8">
                                <p class="text-[10px] uppercase tracking-[0.3em] text-stone-400 font-bold mb-2">AVAILABLE POINTS</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-5xl font-bold text-white">{{ Auth::user()->loyalty_points ?? 0 }}</span>
                                    <span class="text-amber-500 font-bold text-lg">PTS</span>
                                </div>
                                
                                <div class="mt-6">
                                    <div class="flex justify-between text-[10px] font-bold mb-2">
                                        <span class="text-stone-500 uppercase tracking-widest">Next Reward Progress</span>
                                        <span class="text-amber-500">{{ (Auth::user()->loyalty_points ?? 0) % 50 }}/50</span>
                                    </div>
                                    <div class="w-full bg-stone-800 rounded-full h-1.5 overflow-hidden">
                                        @php
                                            $points = Auth::user()->loyalty_points ?? 0;
                                            $percentage = min(($points % 50) * 2, 100);
                                        @endphp
                                        <div class="bg-amber-500 h-full rounded-full transition-all duration-1000" 
                                             :style="{ width: '{{ $percentage }}%' }"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-stone-800">
                                <a href="{{ route('rewards.index') }}" class="flex items-center justify-between group/link">
                                    <span class="text-sm font-bold tracking-tight">Redeem Rewards</span>
                                    <svg class="w-5 h-5 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/10 rounded-[2rem] p-6 border border-amber-100 dark:border-amber-900/20 relative overflow-hidden">
                        <div class="absolute -right-2 -bottom-2 text-amber-200/50 dark:text-amber-800/20 transform -rotate-12">
                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-amber-900 dark:text-amber-500 font-black text-xs uppercase tracking-widest mb-2">Did you enjoy your brew?</h4>
                            <p class="text-stone-600 dark:text-stone-400 text-xs mb-4 leading-relaxed">Rate your recent orders and get <span class="font-bold text-amber-600">+2 points</span> instantly.</p>
                            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition shadow-sm">
                                Rate & Earn
                            </a>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-stone-900 rounded-[2rem] p-6 border border-stone-100 dark:border-stone-800 shadow-sm">
                        <h3 class="font-bold text-stone-900 dark:text-white mb-6 px-2">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-stone-50 dark:bg-stone-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors group">
                                <div class="w-10 h-10 bg-white dark:bg-stone-700 rounded-xl flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">☕</div>
                                <span class="text-xs font-bold text-stone-700 dark:text-stone-300">Order Now</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-stone-50 dark:bg-stone-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors group">
                                <div class="w-10 h-10 bg-white dark:bg-stone-700 rounded-xl flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">👤</div>
                                <span class="text-xs font-bold text-stone-700 dark:text-stone-300">Profile</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-stone-900 rounded-[2rem] border border-stone-100 dark:border-stone-800 shadow-sm overflow-hidden min-h-full">
                        <div class="p-8 border-b border-stone-100 dark:border-stone-800 flex justify-between items-center">
                            <h3 class="font-bold text-xl text-stone-900 dark:text-white">Recent Orders</h3>
                            <a href="{{ route('orders.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-500 transition">View All</a>
                        </div>

                        <div class="p-0">
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                @foreach($recentOrders as $order)
                                    <div class="p-6 border-b border-stone-50 dark:border-stone-800/50 hover:bg-stone-50/50 dark:hover:bg-stone-800/20 transition-colors">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-xl">
                                                    {{ $order->status == 'completed' ? '✅' : '⏳' }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-stone-900 dark:text-white">Order #{{ $order->id }}</p>
                                                    <p class="text-xs text-stone-500 dark:text-stone-400">{{ $order->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-stone-900 dark:text-white">₱{{ number_format($order->total_price, 0) }}</p>
                                                <span class="text-[10px] uppercase tracking-widest font-black text-amber-600">
                                                    {{ $order->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="ml-16 space-y-2">
                                            @foreach($order->items as $item)
                                            <div class="flex items-center justify-between group/item">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-medium text-stone-600 dark:text-stone-400">{{ $item->product->name }}</span>
                                                    
                                                    <div class="flex items-center gap-0.5">
                                                        @if($item->review)
                                                            @foreach(range(1, 5) as $star)
                                                                <svg class="w-3 h-3 {{ $star <= $item->review->rating ? 'text-amber-500' : 'text-stone-200 dark:text-stone-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endforeach
                                                        @else
                                                            <span class="text-[9px] text-stone-400 italic">Not rated</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if(!$item->review && $order->status == 'completed')
                                                    <a href="{{ route('orders.index') }}" class="opacity-0 group-hover/item:opacity-100 transition-opacity text-[10px] font-bold text-amber-600 underline">Rate & Earn 2pts</a>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-20 text-center">
                                    <div class="text-6xl mb-6">☕</div>
                                    <h4 class="text-stone-900 dark:text-white font-bold mb-2 text-xl">No orders yet</h4>
                                    <p class="text-stone-500 dark:text-stone-400 text-sm mb-10 leading-relaxed">
                                        It looks like you haven't ordered your first brew yet.<br>
                                        Let's fix that!
                                    </p>
                                    <a href="{{ route('home') }}" class="inline-flex px-10 py-4 bg-stone-900 dark:bg-white text-white dark:text-stone-900 rounded-full font-bold hover:bg-amber-600 dark:hover:bg-amber-500 dark:hover:text-white transition-all transform hover:scale-105 shadow-lg">
                                        Browse the Menu
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>