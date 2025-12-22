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
                    <div class="text-2xl font-black text-amber-600">{{ $customer->points ?? 0 }} <span class="text-xs">PTS</span></div>
                </div>
                <div class="bg-white dark:bg-stone-900 p-6 rounded-[2rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                    <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest block mb-1">Total Lifetime Value</span>
                    <div class="text-2xl font-black text-stone-900 dark:text-white">₱{{ number_format($customer->orders->where('status', 'completed')->sum('total_price'), 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="space-y-8">
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
                            <div>
                                <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest mb-1">Member Since</p>
                                <p class="font-bold text-stone-900 dark:text-white">{{ $customer->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border-l-4 border-rose-600 shadow-sm transition-colors">
                        <h3 class="font-black text-xs uppercase tracking-widest text-rose-600 mb-2">Reset Password</h3>
                        <p class="text-[10px] text-stone-500 dark:text-stone-400 mb-6 font-medium">
                            Set a new temporary password for this member.
                        </p>

                        @if(session('success'))
                            <div class="mb-4 p-3 bg-green-500/10 rounded-xl text-green-600 text-[10px] font-black uppercase">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.customers.reset_password', $customer->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="block text-[9px] font-black text-stone-400 uppercase tracking-widest mb-2">New Password</label>
                                <input type="password" name="new_password" required 
                                    class="w-full rounded-xl border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white shadow-inner focus:border-rose-500 focus:ring-rose-500 text-sm transition-all">
                            </div>

                            <div class="mb-6">
                                <label class="block text-[9px] font-black text-stone-400 uppercase tracking-widest mb-2">Confirm Password</label>
                                <input type="password" name="new_password_confirmation" required 
                                    class="w-full rounded-xl border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white shadow-inner focus:border-rose-500 focus:ring-rose-500 text-sm transition-all">
                            </div>

                            <button type="submit" class="w-full bg-stone-900 dark:bg-rose-600 hover:bg-rose-700 text-white py-3 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/10 transition transform active:scale-95">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                    <h3 class="font-black text-xs uppercase tracking-widest text-stone-900 dark:text-white mb-8 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Order History
                    </h3>

                    @if($customer->orders->isEmpty())
                        <div class="text-center py-20">
                            <p class="font-serif italic text-2xl text-stone-300 dark:text-stone-700">No coffee journeys yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-stone-400 dark:text-stone-500 text-[9px] font-black uppercase tracking-[0.2em] border-b border-stone-100 dark:border-stone-800">
                                        <th class="pb-4 px-2">Order ID</th>
                                        <th class="pb-4 px-2">Date</th>
                                        <th class="pb-4 px-2">Status</th>
                                        <th class="pb-4 px-2">Items</th>
                                        <th class="pb-4 px-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-50 dark:divide-stone-800/50">
                                    @foreach($customer->orders as $order)
                                        <tr class="group">
                                            <td class="py-6 px-2 font-black text-stone-900 dark:text-white text-sm">#{{ $order->id }}</td>
                                            <td class="py-6 px-2 text-stone-500 text-[11px] font-medium">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="py-6 px-2">
                                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest
                                                    {{ $order->status == 'completed' ? 'bg-green-500/10 text-green-600' : '' }}
                                                    {{ $order->status == 'pending' ? 'bg-amber-500/10 text-amber-600' : '' }}
                                                    {{ $order->status == 'cancelled' ? 'bg-rose-500/10 text-rose-600' : '' }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="py-6 px-2">
                                                <div class="flex flex-col gap-1">
                                                    @foreach($order->items as $item)
                                                        <span class="text-[10px] text-stone-600 dark:text-stone-400 font-medium">
                                                            <strong class="text-stone-900 dark:text-white">{{ $item->quantity }}x</strong> {{ $item->product->name ?? 'Deleted Item' }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-6 px-2 font-black text-right text-stone-900 dark:text-white">
                                                ₱{{ number_format($order->total_price ?? $order->total_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>