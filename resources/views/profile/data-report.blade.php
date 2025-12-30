<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-stone-900 dark:text-white uppercase tracking-tight italic">
                Personal Data Report
            </h2>
            <button onclick="window.print()" class="px-6 py-2 bg-stone-900 text-white text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-amber-600 transition-all no-print">
                Print Report
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-2xl overflow-hidden p-10 sm:p-16">
                
                {{-- Report Header --}}
                <div class="flex flex-col md:flex-row justify-between gap-8 mb-16 border-b border-stone-100 dark:border-stone-800 pb-12">
                    <div>
                        <div class="h-12 w-12 rounded-full overflow-hidden mb-6">
                            <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <h1 class="text-3xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic">Mik's Coffee Shop</h1>
                        <p class="text-xs text-stone-500 font-bold uppercase tracking-widest mt-1">Privacy Compliance Document</p>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em] block mb-2">Subject Information</span>
                        <p class="text-xl font-bold text-stone-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-sm text-stone-500 italic">{{ $user->email }}</p>
                        <p class="text-[10px] text-stone-400 font-bold uppercase mt-4">Issued: {{ now()->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                {{-- Account Summary --}}
                <section class="mb-16">
                    <h3 class="text-sm font-black text-stone-900 dark:text-white uppercase tracking-[0.4em] mb-8 border-l-4 border-amber-500 pl-4">I. Account Summary</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-stone-50 dark:bg-stone-950 p-6 rounded-3xl border border-stone-100 dark:border-stone-800">
                            <p class="text-[9px] font-black text-stone-400 uppercase mb-2">Loyalty Points</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white italic">{{ $user->points }} PTS</p>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-950 p-6 rounded-3xl border border-stone-100 dark:border-stone-800">
                            <p class="text-[9px] font-black text-stone-400 uppercase mb-2">Active Streak</p>
                            <p class="text-2xl font-black text-stone-900 dark:text-white italic">{{ $user->streak_count }} Days</p>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-950 p-6 rounded-3xl border border-stone-100 dark:border-stone-800">
                            <p class="text-[9px] font-black text-stone-400 uppercase mb-2">Member Since</p>
                            <p class="text-sm font-black text-stone-900 dark:text-white italic uppercase">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </section>

                {{-- Transaction History --}}
                <section class="mb-16">
                    <h3 class="text-sm font-black text-stone-900 dark:text-white uppercase tracking-[0.4em] mb-8 border-l-4 border-amber-500 pl-4">II. Order Activity</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-stone-400 uppercase tracking-widest border-b border-stone-100 dark:border-stone-800">
                                    <th class="pb-4">Order ID</th>
                                    <th class="pb-4">Items</th>
                                    <th class="pb-4">Status</th>
                                    <th class="pb-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-50 dark:divide-stone-800/50">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="py-4 text-xs font-black text-stone-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="py-4 text-xs text-stone-500 italic">
                                            @foreach($order->items as $item)
                                                {{ $item->product->name ?? 'Brew' }} ({{ $item->quantity }}){{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </td>
                                        <td class="py-4">
                                            <span class="text-[9px] font-black uppercase text-amber-600">{{ $order->status }}</span>
                                        </td>
                                        <td class="py-4 text-right text-xs font-black text-stone-900 dark:text-white">₱{{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- Legal Notice --}}
                <div class="mt-20 p-8 bg-stone-950 text-white rounded-[2rem] border border-stone-800 relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-500 mb-4">Legal Disclaimer</h4>
                        <p class="text-[10px] text-stone-400 font-medium italic leading-relaxed uppercase tracking-tight">
                            This report is generated in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173). It contains personal data and transaction records exclusively linked to the authenticated user. Mik's Coffee Shop maintains these records for the primary purpose of providing loyalty rewards and order fulfillment services in our Trece Martires branch.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .shadow-2xl { box-shadow: none !important; }
        }
    </style>
</x-app-layout>