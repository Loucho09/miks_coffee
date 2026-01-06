<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-600 flex items-center justify-center font-serif italic text-2xl text-white shadow-lg shadow-amber-600/20">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="font-black text-2xl text-stone-900 dark:text-white uppercase tracking-tight leading-none">
                        {{ $customer->name }}
                    </h2>
                    <p class="text-[10px] text-amber-600 font-black uppercase tracking-[0.2em] mt-1">Member Profile</p>
                </div>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="text-[10px] font-black uppercase tracking-widest text-stone-400 hover:text-amber-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest block mb-1">Total Orders</span>
                    <div class="text-2xl font-black text-stone-900 dark:text-white">{{ $customer->orders->count() }}</div>
                </div>
                <div class="bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest block mb-1">Loyalty Points</span>
                    <div class="text-2xl font-black text-amber-600">{{ $customer->loyalty_points ?? 0 }} <span class="text-xs">PTS</span></div>
                </div>
                <div class="bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest block mb-1">Total Lifetime Value</span>
                    <div class="text-2xl font-black text-stone-900 dark:text-white">₱{{ number_format($customer->orders->where('status', 'completed')->sum('total_price'), 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="space-y-8">
                    <div class="bg-stone-900 rounded-[2.5rem] p-8 border border-stone-800 shadow-2xl relative overflow-hidden group">
                        <p class="text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-10 italic">Engagement Data</p>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.4em] text-stone-500 font-black mb-2">Current Streak</p>
                                <p class="text-4xl font-black italic text-white">{{ $customer->streak_count }} Days</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                        <h3 class="font-black text-xs uppercase tracking-widest text-stone-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Account Details
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest mb-1">Full Name</p>
                                <p class="font-bold text-stone-900 dark:text-white">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest mb-1">Email Address</p>
                                <p class="font-bold text-stone-900 dark:text-white">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                        <h3 class="font-black text-xs uppercase tracking-widest text-stone-900 dark:text-white mb-8 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Point Ledger
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-stone-400 dark:text-stone-500 text-[9px] font-black uppercase tracking-[0.2em] border-b border-stone-100 dark:border-stone-800">
                                        <th class="pb-4 px-2">Transaction Detail</th>
                                        <th class="pb-4 px-2">Date</th>
                                        <th class="pb-4 px-2 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-50 dark:divide-stone-800/50">
                                    @forelse($customer->pointTransactions as $tx)
                                        <tr>
                                            <td class="py-4 px-2 text-sm font-bold uppercase text-stone-600 dark:text-stone-300 tracking-tight">{{ $tx->description }}</td>
                                            <td class="py-4 px-2 text-[10px] font-black text-stone-400 uppercase italic">{{ $tx->created_at->format('M d, Y') }}</td>
                                            <td class="py-4 px-2 text-right">
                                                <span class="font-black italic {{ $tx->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                                    {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }} PTS
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-12 text-center opacity-30 italic font-black uppercase text-stone-500 tracking-widest text-[9px]">No transactions on record</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>