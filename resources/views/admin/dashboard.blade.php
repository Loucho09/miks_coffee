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
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400">Live Insights</span>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(16,185,129,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Total Revenue</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">
                                ₱{{ number_format($totalRevenue, 2) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 transition-all duration-500">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Total Orders</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalOrders }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:bg-blue-500 transition-all duration-500">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Pending</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $pendingOrders }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20 group-hover:bg-amber-500 transition-all duration-500">
                            <svg class="w-6 h-6 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(168,85,247,0.1)]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-1">Customers</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white tracking-tighter">{{ $totalCustomers }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 group-hover:bg-purple-500 transition-all duration-500">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
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
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-[100px]"></div>
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
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-amber-600/10 rounded-full blur-3xl group-hover:bg-amber-600/20 transition-all"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 overflow-hidden shadow-sm">
                    <div class="px-10 py-8 border-b border-stone-100 dark:border-stone-800 flex justify-between items-center bg-stone-50/50 dark:bg-transparent">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-1 block">Activity</span>
                            <h3 class="text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Recent Orders</h3>
                        </div>
                        <a href="{{ route('barista.queue') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-600 transition-all">Queue &rarr;</a>
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
                                        <td class="px-10 py-6 font-medium">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</td>
                                        <td class="px-10 py-6">
                                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                {{ $order->status === 'pending' ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : 
                                                 ($order->status === 'preparing' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20') }}">
                                                <span class="w-1 h-1 rounded-full bg-current animate-pulse"></span>
                                                {{ $order->status }}
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-right font-black text-stone-900 dark:text-white text-lg">₱{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-20 text-center text-stone-400 font-light italic">No activity recorded today.</td>
                                    </tr>
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
                            <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-6">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-xs font-black uppercase tracking-[0.2em]">Inventory Healthy</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($lowStockItems as $item)
                                <div class="flex justify-between items-center bg-rose-500/5 dark:bg-rose-500/10 p-5 rounded-2xl border border-rose-500/10 group hover:border-rose-500/40 transition-all">
                                    <span class="text-sm font-bold text-stone-800 dark:text-stone-200">{{ $item->name }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-rose-600 bg-white dark:bg-stone-800 px-3 py-1 rounded-full shadow-sm">
                                            {{ $item->stock_quantity }} Left
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-10">
                            <a href="{{ route('admin.menu.index') }}" class="block w-full text-center py-4 bg-stone-900 dark:bg-white text-white dark:text-stone-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-amber-600 dark:hover:bg-amber-500 dark:hover:text-white transition-all shadow-xl">
                                Restock Inventory
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                            backgroundColor: '#D97706', // Amber 600
                            borderRadius: 12,
                            borderSkipped: false,
                            barThickness: 25
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { 
                            y: { grid: { display: false }, ticks: { font: { family: 'Outfit' } } },
                            x: { grid: { display: false }, ticks: { font: { family: 'Outfit' } } }
                        }
                    }
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
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: { 
                            legend: { position: 'bottom', labels: { color: '#ffffff', font: { family: 'Outfit', size: 10 } } } 
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>