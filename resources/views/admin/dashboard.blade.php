<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-1 block">Management</span>
                <h2 class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white leading-tight tracking-tight uppercase transition-colors duration-500">
                    {{ __('Analytics Dashboard') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400 dark:text-stone-500">Real-Time Sales Feed</span>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 sm:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 🟢 NEW FEATURE: Admin Daily Snapshot --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-10">
                {{-- Today's Sales Card --}}
                <div class="bg-stone-900 dark:bg-amber-600 p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group transition-all duration-500 hover:shadow-amber-600/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-stone-400 dark:text-amber-900/60 mb-2">Today's Revenue</p>
                        <h4 class="text-3xl font-black mb-1">₱{{ number_format($todayRevenue, 2) }}</h4>
                        <p class="text-[10px] font-bold {{ $revenueChange >= 0 ? 'text-emerald-400 dark:text-amber-100' : 'text-rose-400 dark:text-stone-900' }}">
                            {{ $revenueChange >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueChange), 1) }}% vs Yesterday
                        </p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-white/5 dark:text-black/5 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>

                {{-- Loyalty Issued Card --}}
                <div class="bg-white dark:bg-stone-900 p-6 sm:p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm transition-all duration-500 flex flex-col justify-center">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Points Issued Today</p>
                    <h4 class="text-3xl font-black text-amber-600 dark:text-amber-500 italic">+{{ number_format($pointsIssued) }}</h4>
                    <p class="text-[9px] font-bold text-stone-400 dark:text-stone-500 uppercase mt-1">New Loyalty Debt</p>
                </div>

                {{-- Redemptions Card --}}
                <div class="bg-white dark:bg-stone-900 p-6 sm:p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm transition-all duration-500 flex flex-col justify-center">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Rewards Redeemed</p>
                    <h4 class="text-3xl font-black text-rose-600 dark:text-rose-500 italic">-{{ number_format($pointsRedeemed) }}</h4>
                    <p class="text-[9px] font-bold text-stone-400 dark:text-stone-500 uppercase mt-1">Inventory Outflow</p>
                </div>

                {{-- Top Customer Card --}}
                <div class="bg-amber-100 dark:bg-stone-800 p-6 sm:p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden group flex flex-col justify-center transition-all duration-500 border border-amber-200 dark:border-stone-700">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-amber-800 dark:text-amber-500/80 uppercase tracking-[0.3em] mb-2">Today's Top Regular</p>
                        <h4 class="text-xl font-black text-stone-900 dark:text-white uppercase italic leading-none truncate">
                            {{ $topCustomer && $topCustomer->orders_count > 0 ? $topCustomer->name : 'No Orders' }}
                        </h4>
                        <p class="text-[9px] font-bold text-amber-700 dark:text-stone-400 uppercase mt-2">Most active customer</p>
                    </div>
                    <div class="absolute -bottom-2 -right-2 text-5xl opacity-10 dark:opacity-20 transition-transform group-hover:scale-110">🏆</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-10">
                <div class="bg-white dark:bg-stone-900 p-6 sm:p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm flex flex-col justify-center transition-all duration-500">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Pending Prep</p>
                    <h4 class="text-3xl sm:text-4xl font-black text-stone-900 dark:text-white">{{ $pendingOrders }}</h4>
                    <p class="text-xs text-amber-600 font-bold mt-2">Active in Kitchen</p>
                </div>

                <div class="bg-stone-100 dark:bg-stone-900 p-6 sm:p-8 rounded-[2.5rem] shadow-sm flex flex-col justify-center border border-stone-200 dark:border-stone-800 transition-all duration-500">
                    <p class="text-[10px] font-black text-stone-500 dark:text-stone-400 uppercase tracking-[0.3em] mb-2">Total Customer Base</p>
                    <h4 class="text-3xl sm:text-4xl font-black text-stone-900 dark:text-white">{{ $totalCustomers }}</h4>
                    <p class="text-xs text-stone-500 font-bold mt-2 tracking-tight">Registered Accounts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10 mb-8 sm:mb-10">
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-6 sm:p-10 shadow-sm relative overflow-hidden transition-all duration-500">
                    <div class="relative z-10 mb-8 sm:mb-10 flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-1 block">Performance</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Weekly Sales</h3>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-800 px-4 py-2 rounded-2xl border border-stone-200 dark:border-stone-700 transition-colors">
                            <p class="text-[8px] font-black uppercase text-stone-400 tracking-widest mb-0.5">Peak Traffic</p>
                            <p class="text-[10px] sm:text-xs font-black text-emerald-600 dark:text-emerald-400 tracking-widest uppercase">08:00 AM - 11:00 AM</p>
                        </div>
                    </div>
                    <div class="relative z-10 w-full" style="height: 300px; min-height: 250px;">
                        <canvas id="revenueChart" data-labels='@json($revenueLabels)' data-values='@json($revenueData)'></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-6 sm:p-10 shadow-sm relative overflow-hidden group transition-all duration-500">
                    <div class="relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-1 block">Categories</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight mb-8 sm:mb-10">Top Performers</h3>
                        <div class="relative w-full" style="height: 250px;">
                            <canvas id="categoryChart" data-labels='@json($categoryLabels)' data-values='@json($categoryData)'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 overflow-hidden shadow-sm transition-all duration-500">
                    <div class="px-6 sm:px-10 py-6 sm:py-8 border-b border-stone-100 dark:border-stone-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-1 block">Activity</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Recent Orders</h3>
                        </div>
                        <a href="{{ route('admin.orders.export') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-stone-900 dark:bg-stone-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg active:scale-95 shadow-stone-900/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Data
                        </a>
                    </div>
                    <div class="overflow-x-auto scrolling-touch">
                        <table class="w-full text-left min-w-[500px]">
                            <thead class="bg-stone-50 dark:bg-stone-800/50 text-stone-400 dark:text-stone-500 text-[10px] font-black uppercase tracking-widest transition-colors">
                                <tr>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5">Order ID</th>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5">Customer</th>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-sm transition-colors">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-stone-50/80 dark:hover:bg-stone-800/40 transition-colors">
                                        <td class="px-6 sm:px-10 py-5 sm:py-6 font-black text-stone-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="px-6 sm:px-10 py-5 sm:py-6 font-medium text-stone-600 dark:text-stone-400">{{ $order->user->name ?? 'Guest' }}</td>
                                        <td class="px-6 sm:px-10 py-5 sm:py-6 text-right font-black text-stone-900 dark:text-white">₱{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-10 py-20 text-center text-stone-400 italic font-medium">No activity today.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-6 sm:p-10 shadow-sm h-fit transition-all duration-500">
                    <div class="mb-8">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-rose-500 mb-1 block">Inventory</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Stock Alerts</h3>
                    </div>
                    @if($lowStockItems->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10 text-emerald-500/80 dark:text-emerald-500/50 transition-colors">
                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]">All Stocks OK</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($lowStockItems as $item)
                                <div class="flex justify-between items-center bg-rose-50 dark:bg-rose-500/10 p-4 rounded-2xl border border-rose-100 dark:border-rose-500/20 transition-colors">
                                    <span class="text-sm font-bold text-stone-800 dark:text-stone-200 truncate pr-2">{{ $item->name }}</span>
                                    <span class="flex-shrink-0 text-[9px] font-black text-rose-600 dark:text-rose-400 bg-white dark:bg-stone-800 px-2.5 py-1 rounded-full shadow-sm border border-rose-100 dark:border-rose-900/50">
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
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
            const tickColor = isDark ? '#78716c' : '#a8a29e';

            setTimeout(() => { window.location.reload(); }, 60000);
            
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
                            backgroundColor: isDark ? '#fbbf24' : '#stone-900',
                            backgroundColor: ['#d97706', '#f59e0b', '#fbbf24', '#fcd34d', '#fde68a'],
                            borderRadius: 12
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, 
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                grid: { color: gridColor }, 
                                border: { display: false },
                                ticks: { color: tickColor, font: { weight: '700', size: 10 } } 
                            },
                            x: { 
                                grid: { display: false }, 
                                border: { display: false },
                                ticks: { color: tickColor, font: { weight: '700', size: 10 } } 
                            }
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
                            backgroundColor: ['#92400e', '#b45309', '#d97706', '#f59e0b', '#fbbf24'],
                            borderWidth: isDark ? 4 : 2,
                            borderColor: isDark ? '#1c1917' : '#ffffff'
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        cutout: '75%', 
                        plugins: { 
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    color: tickColor,
                                    font: { size: 10, weight: '700' }, 
                                    padding: 20,
                                    usePointStyle: true
                                } 
                            } 
                        } 
                    }
                });
            }
        });
    </script>
</x-app-layout>