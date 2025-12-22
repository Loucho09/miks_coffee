<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-1 block">Management</span>
                <h2 class="font-black text-3xl text-stone-900 dark:text-white leading-tight tracking-tight uppercase">
                    {{ __('Analytics Dashboard') }}
                </h2>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400">Real-Time Sales Feed</span>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-amber-600 p-8 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-80 mb-2">Today's Sales Summary</p>
                        <h4 class="text-4xl font-black mb-1">₱{{ number_format($todayRevenue, 2) }}</h4>
                        <div class="flex items-center gap-2 mt-4">
                            <span class="px-2 py-1 bg-white/20 rounded-lg text-[10px] font-bold">
                                {{ $todayOrderCount }} Orders
                            </span>
                            <span class="text-[10px] font-bold {{ $revenueChange >= 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                                {{ $revenueChange >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueChange), 1) }}% vs Yesterday
                            </span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm flex flex-col justify-center">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Pending Prep</p>
                    <h4 class="text-4xl font-black text-stone-900 dark:text-white">{{ $pendingOrders }}</h4>
                    <p class="text-xs text-amber-600 font-bold mt-2">Active in Kitchen</p>
                </div>

                <div class="bg-stone-900 p-8 rounded-[2.5rem] shadow-2xl flex flex-col justify-center border border-stone-800">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Total Customer Base</p>
                    <h4 class="text-4xl font-black text-white">{{ $totalCustomers }}</h4>
                    <p class="text-xs text-stone-500 font-bold mt-2">Registered Accounts</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(16,185,129,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Gross Revenue</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">
                                ₱{{ number_format($totalRevenue, 2) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 transition-all duration-500 text-emerald-600 group-hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">All-Time Orders</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalOrders }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:bg-blue-500 transition-all duration-500 text-blue-600 group-hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Pending</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $pendingOrders }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20 group-hover:bg-amber-50 transition-all duration-500 text-amber-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(168,85,247,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Customers</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalCustomers }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 group-hover:bg-purple-500 transition-all duration-500 text-purple-600 group-hover:text-white">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-10">
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-10 shadow-sm relative overflow-hidden">
                    <div class="relative z-10 mb-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-1 block">Performance</span>
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Weekly Sales</h3>
                    </div>
                    <div class="relative z-10" style="height: 350px;">
                        <canvas id="revenueChart" 
                                data-labels='@json($revenueLabels)' 
                                data-values='@json($revenueData)'>
                        </canvas>
                    </div>
                </div>

                <div class="bg-stone-900 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-1 block">Categories</span>
                        <h3 class="text-2xl font-bold text-white tracking-tight mb-10">Top Performers</h3>
                        <div class="relative" style="height: 250px;">
                            <canvas id="categoryChart" 
                                    data-labels='@json($categoryLabels)' 
                                    data-values='@json($categoryData)'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 overflow-hidden shadow-sm">
                    <div class="px-10 py-8 border-b border-stone-100 dark:border-stone-800 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-1 block">Activity</span>
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Recent Orders</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-stone-50 dark:bg-stone-800 text-stone-400 text-[10px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-10 py-5">Order ID</th>
                                    <th class="px-10 py-5">Customer</th>
                                    <th class="px-10 py-5">Status</th>
                                    <th class="px-10 py-5 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-sm text-stone-700 dark:text-stone-300">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-stone-50/80 dark:hover:bg-stone-800/40 transition-colors">
                                        <td class="px-10 py-6 font-black text-stone-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="px-10 py-6 font-medium">{{ $order->user->name ?? 'Guest' }}</td>
                                        <td class="px-10 py-6">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                {{ $order->status === 'pending' ? 'bg-rose-500/10 text-rose-500' : 'bg-emerald-500/10 text-emerald-500' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-6 text-right font-black">₱{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-10 py-20 text-center text-stone-400 italic">No activity today.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 p-10 shadow-sm">
                    <div class="mb-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-rose-500 mb-1 block">Inventory</span>
                        <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Stock Alerts</h3>
                    </div>
                    
                    @if($lowStockItems->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10 text-emerald-500">
                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-xs font-black uppercase tracking-[0.2em]">All Stocks OK</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($lowStockItems as $item)
                                <div class="flex justify-between items-center bg-rose-500/5 p-5 rounded-2xl border border-rose-500/10">
                                    <span class="text-sm font-bold">{{ $item->name }}</span>
                                    <span class="text-[10px] font-black text-rose-600 bg-white px-3 py-1 rounded-full shadow-sm">
                                        {{ $item->stock_quantity }} left
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Real-Time Update Logic (Refresh page every 60 seconds)
            setTimeout(() => {
                window.location.reload();
            }, 60000);

            const revenueCanvas = document.getElementById('revenueChart');
            const categoryCanvas = document.getElementById('categoryChart');

            if (revenueCanvas) {
                new Chart(revenueCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: JSON.parse(revenueCanvas.dataset.labels),
                        datasets: [{
                            label: 'Revenue',
                            data: JSON.parse(revenueCanvas.dataset.values),
                            backgroundColor: '#D97706',
                            borderRadius: 12
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            if (categoryCanvas) {
                new Chart(categoryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: JSON.parse(categoryCanvas.dataset.labels),
                        datasets: [{
                            data: JSON.parse(categoryCanvas.dataset.values),
                            backgroundColor: ['#D97706', '#8D5F46', '#4B2C20', '#A39284', '#F7F3F0'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%' }
                });
            }
        });
    </script>
</x-app-layout>