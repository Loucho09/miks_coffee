<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        
        <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-emerald-500/50 hover:shadow-[0_30px_60px_-20px_rgba(16,185,129,0.15)]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-2 block">Revenue</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white tracking-tighter">₱{{ number_format($totalRevenue, 2) }}</h3>
                    <div class="mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">+12% vs last month</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:rotate-6 transition-all duration-500 shadow-inner">
                    <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-500/[0.02] rounded-[2.5rem] pointer-events-none"></div>
        </div>

        <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-blue-500/50 hover:shadow-[0_30px_60px_-20px_rgba(59,130,246,0.15)]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-2 block">Volume</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalOrders }}</h3>
                    <div class="mt-2 flex items-center gap-1 text-stone-400">
                        <span class="text-[9px] font-black uppercase tracking-widest">Total Completed</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:bg-blue-500 group-hover:-rotate-6 transition-all duration-500 shadow-inner">
                    <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-500/[0.02] rounded-[2.5rem] pointer-events-none"></div>
        </div>

        <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-amber-500/50 hover:shadow-[0_30px_60px_-20px_rgba(245,158,11,0.15)]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-2 block">Queue</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $pendingOrders }}</h3>
                    <div class="mt-2 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Live Orders</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20 group-hover:bg-amber-500 group-hover:scale-110 transition-all duration-500 shadow-inner">
                    <svg class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-amber-500/[0.02] rounded-[2.5rem] pointer-events-none"></div>
        </div>

        <div class="group relative p-8 rounded-[2.5rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:border-purple-500/50 hover:shadow-[0_30px_60px_-20px_rgba(168,85,247,0.15)]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-2 block">Loyalty</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalCustomers }}</h3>
                    <div class="mt-2 flex items-center gap-1">
                        <span class="text-[9px] font-black text-purple-500 uppercase tracking-widest">Total Community Members</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 group-hover:bg-purple-500 transition-all duration-500 shadow-inner">
                    <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-500/[0.02] rounded-[2.5rem] pointer-events-none"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 p-10 rounded-[3rem] bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <div class="mb-10">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-2 block">Growth Analytics</span>
                <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Sales Performance</h3>
            </div>
            <div class="relative h-[350px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="p-10 rounded-[3rem] bg-stone-900 text-white shadow-2xl relative overflow-hidden group border border-stone-800">
            <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-[80px] -mr-20 -mt-20 group-hover:bg-amber-500/20 transition-all duration-700"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-2 block">Performance</span>
                <h3 class="text-2xl font-bold mb-10 tracking-tight font-serif italic">Best Sellers</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-5 rounded-2xl bg-stone-800/40 border border-stone-700 hover:border-amber-500/30 transition-all duration-300 group/item">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black text-amber-500/40 group-hover/item:text-amber-500 transition-colors">01</span>
                            <span class="text-sm font-bold tracking-tight">Iced Caramel Latte</span>
                        </div>
                        <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest bg-amber-500/10 px-2 py-1 rounded-lg">Top Pick</span>
                    </div>

                    <div class="flex items-center justify-between p-5 rounded-2xl bg-stone-800/40 border border-stone-700 hover:border-amber-500/30 transition-all duration-300 group/item">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black text-amber-500/40 group-hover/item:text-amber-500 transition-colors">02</span>
                            <span class="text-sm font-bold tracking-tight">Spanish Latte</span>
                        </div>
                        <span class="text-[8px] font-black text-stone-500 uppercase tracking-widest">Rising</span>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-stone-800">
                    <p class="text-[10px] text-stone-500 font-bold uppercase tracking-[0.2em] mb-4 text-center tracking-[0.4em]">System Status</p>
                    <div class="flex justify-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-stone-700"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-stone-700"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>