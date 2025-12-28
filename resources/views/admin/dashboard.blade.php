<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-1 block">Management</span>
                <h2 class="font-black text-2xl sm:text-3xl text-stone-900 dark:text-white leading-tight tracking-tight uppercase">
                    {{ __('Analytics Dashboard') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400">Real-Time Sales Feed</span>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 sm:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">
                <div class="bg-stone-500 p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-80 mb-2">Today's Sales Summary</p>
                        <h4 class="text-3xl sm:text-4xl font-black mb-1">₱{{ number_format($todayRevenue, 2) }}</h4>
                        
                        <div class="mt-4 mb-2">
                            <div class="flex justify-between text-[9px] font-black uppercase tracking-tighter mb-1 opacity-80">
                                <span>Daily Goal Progress</span>
                                <span>₱10k Goal</span>
                            </div>
                            @php $percentage = min(($todayRevenue / 10000) * 100, 100); @endphp
                            <div class="w-full bg-white/20 rounded-full h-1.5 overflow-hidden">
                                {{-- ALTERNATIVE FIX: Uses Tailwind class instead of style attribute to clear diagnostic errors --}}
                                <div class="bg-white h-full transition-all duration-1000 w-[{{ $percentage }}%]"></div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            <span class="px-2 py-1 bg-white/20 rounded-lg text-[10px] font-bold">
                                {{ $todayOrderCount }} Orders
                            </span>
                            <span class="text-[10px] font-bold {{ $revenueChange >= 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                                {{ $revenueChange >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueChange), 1) }}% vs Yesterday
                            </span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-24 h-24 sm:w-32 sm:h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm flex flex-col justify-center">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Pending Prep</p>
                    <h4 class="text-3xl sm:text-4xl font-black text-stone-900 dark:text-white">{{ $pendingOrders }}</h4>
                    <p class="text-xs text-amber-600 font-bold mt-2">Active in Kitchen</p>
                </div>

                <div class="bg-stone-900 p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl flex flex-col justify-center border border-stone-800 sm:col-span-2 lg:col-span-1">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2">Total Customer Base</p>
                    <h4 class="text-3xl sm:text-4xl font-black text-white">{{ $totalCustomers }}</h4>
                    <p class="text-xs text-stone-500 font-bold mt-2">Registered Accounts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10 mb-8 sm:mb-10">
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-6 sm:p-10 shadow-sm relative overflow-hidden">
                    <div class="relative z-10 mb-8 sm:mb-10 flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-stone-400 mb-1 block">Performance</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Weekly Sales</h3>
                        </div>
                        <div class="bg-stone-100 dark:bg-stone-800 px-4 py-2 rounded-2xl border border-stone-200 dark:border-stone-700">
                            <p class="text-[8px] font-black uppercase text-stone-500 dark:text-stone-400 tracking-widest mb-0.5">Peak Traffic</p>
                            <p class="text-[10px] sm:text-xs font-black text-green-500 tracking-widest uppercase">08:00 AM - 11:00 AM</p>
                        </div>
                    </div>
                    <div class="relative z-10 w-full" style="height: 300px; min-height: 250px;">
                        <canvas id="revenueChart" data-labels='@json($revenueLabels)' data-values='@json($revenueData)'></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-200 light:border-dandelion-100 dark:border-stone-800 p-6 sm:p-10 shadow-sm relative overflow-hidden group">
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
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 overflow-hidden shadow-sm">
                    <div class="px-6 sm:px-10 py-6 sm:py-8 border-b border-stone-100 dark:border-stone-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-1 block">Activity</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Recent Orders</h3>
                        </div>
                        <a href="{{ route('admin.orders.export') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-stone-900 dark:bg-stone-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Data
                        </a>
                    </div>
                    <div class="overflow-x-auto scrolling-touch">
                        <table class="w-full text-left min-w-[500px]">
                            <thead class="bg-stone-50 dark:bg-stone-800/50 text-stone-400 text-[10px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5">Order ID</th>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5">Customer</th>
                                    <th class="px-6 sm:px-10 py-4 sm:py-5 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-sm">
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

                <div class="bg-white dark:bg-stone-900 rounded-[2rem] sm:rounded-[2.5rem] border border-stone-200 dark:border-stone-800 p-6 sm:p-10 shadow-sm h-fit">
                    <div class="mb-8">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-rose-500 mb-1 block">Inventory</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-white tracking-tight">Stock Alerts</h3>
                    </div>
                    @if($lowStockItems->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10 text-emerald-500/80">
                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]">All Stocks OK</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($lowStockItems as $item)
                                <div class="flex justify-between items-center bg-rose-500/5 dark:bg-rose-500/10 p-4 rounded-2xl border border-rose-500/10 dark:border-rose-500/20">
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
                            backgroundColor: ['#fed639', '#e4c033', '#cbab2d', '#fae1bd', '#d88e21'],
                            borderRadius: 8
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { display: false }, ticks: { font: { weight: '600', size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { weight: '600', size: 10 } } }
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
                            backgroundColor: ['#df8c43', '#e5a369', '#ecba8e', '#f2d1b4', '#f9e8d9'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { font: { size: 10, weight: '700' }, padding: 20 } } } }
                });
            }
        });
    </script>
</x-app-layout>