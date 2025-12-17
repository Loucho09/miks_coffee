<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-stone-800 dark:text-white leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                
                <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 flex items-center justify-between transition hover:shadow-md">
                    <div class="flex items-center gap-4 overflow-hidden">
                        <div class="p-3 shrink-0 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xl">
                            💰
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide truncate">
                                Total Revenue
                            </p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-extrabold text-stone-900 dark:text-white truncate">
                                ₱{{ number_format($totalRevenue, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 flex items-center justify-between transition hover:shadow-md">
                    <div class="flex items-center gap-4 overflow-hidden">
                        <div class="p-3 shrink-0 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xl">
                            📦
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide truncate">
                                Total Orders
                            </p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-extrabold text-stone-900 dark:text-white truncate">
                                {{ $totalOrders }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 flex items-center justify-between transition hover:shadow-md">
                    <div class="flex items-center gap-4 overflow-hidden">
                        <div class="p-3 shrink-0 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xl">
                            ⚡
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide truncate">
                                Pending
                            </p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-extrabold text-stone-900 dark:text-white truncate">
                                {{ $pendingOrders }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 flex items-center justify-between transition hover:shadow-md">
                    <div class="flex items-center gap-4 overflow-hidden">
                        <div class="p-3 shrink-0 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xl">
                            👥
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-bold text-stone-500 dark:text-stone-400 uppercase tracking-wide truncate">
                                Customers
                            </p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-extrabold text-stone-900 dark:text-white truncate">
                                {{ $totalCustomers }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 p-6">
                    <h3 class="font-bold text-lg text-stone-900 dark:text-white mb-6">Weekly Sales Performance</h3>
                    <div class="relative" style="height: 300px; width: 100%;">
                        <canvas id="revenueChart" 
                                data-labels='@json($revenueLabels)' 
                                data-values='@json($revenueData)'>
                        </canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 p-6">
                    <h3 class="font-bold text-lg text-stone-900 dark:text-white mb-6">Top Categories</h3>
                    <div class="relative" style="height: 300px; width: 100%;">
                        <canvas id="categoryChart" 
                                data-labels='@json($categoryLabels)' 
                                data-values='@json($categoryData)'>
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-stone-900 dark:text-white">Recent Orders</h3>
                        <a href="{{ route('barista.queue') }}" class="text-sm text-amber-600 hover:text-amber-700 font-bold">View Queue →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-stone-50 dark:bg-stone-800 text-stone-500 dark:text-stone-400 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3">Order ID</th>
                                    <th class="px-6 py-3">Customer</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-sm text-stone-700 dark:text-stone-300">
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="px-6 py-4 font-bold">#{{ $order->id }}</td>
                                        <td class="px-6 py-4">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                                                {{ $order->status === 'pending' ? 'bg-red-100 text-red-700' : 
                                                 ($order->status === 'preparing' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold">₱{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-stone-500">No orders yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-100 dark:border-stone-800 p-6">
                    <h3 class="font-bold text-lg text-red-600 dark:text-red-400 mb-6 flex items-center">
                        <span class="mr-2">⚡</span> Low Stock Alerts
                    </h3>
                    @if($lowStockItems->isEmpty())
                        <div class="flex flex-col items-center justify-center h-40 text-green-600 dark:text-green-400">
                            <span class="text-3xl mb-2">✅</span>
                            <p class="text-sm font-bold">All Stock Healthy!</p>
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach($lowStockItems as $item)
                                <li class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-3 rounded-xl border border-red-100 dark:border-red-800">
                                    <span class="text-sm font-bold text-stone-700 dark:text-stone-300">{{ $item->name }}</span>
                                    <span class="text-xs font-extrabold text-red-600 bg-white dark:bg-stone-800 px-2 py-1 rounded shadow-sm">
                                        {{ $item->stock_quantity }} left
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-6 text-center">
                            <a href="{{ route('admin.menu.index') }}" class="text-sm text-blue-600 hover:underline">Restock Now &rarr;</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Get Elements
            const revenueCanvas = document.getElementById('revenueChart');
            const categoryCanvas = document.getElementById('categoryChart');

            // 1. Render Revenue Chart
            if (revenueCanvas) {
                new Chart(revenueCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: JSON.parse(revenueCanvas.dataset.labels),
                        datasets: [{
                            label: 'Revenue (₱)',
                            data: JSON.parse(revenueCanvas.dataset.values),
                            backgroundColor: 'rgba(245, 158, 11, 0.6)', // Brand Amber
                            borderColor: 'rgba(245, 158, 11, 1)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // 2. Render Category Chart
            if (categoryCanvas) {
                new Chart(categoryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: JSON.parse(categoryCanvas.dataset.labels),
                        datasets: [{
                            data: JSON.parse(categoryCanvas.dataset.values),
                            backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#EF4444', '#8B5CF6'],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    </script>
</x-app-layout>