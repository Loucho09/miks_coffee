<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    {{ __('Customer Management') }}
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium italic font-serif">
                    Monitor and support your registered coffee community.
                </p>
            </div>
            
            <form method="GET" action="{{ route('admin.customers.index') }}" class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Find a customer..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-none bg-stone-100 dark:bg-stone-800 shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white placeholder-stone-500 transition-all text-sm">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center gap-4">
                <div class="bg-white dark:bg-stone-900 p-6 rounded-3xl border border-stone-200 dark:border-stone-800 flex-1 shadow-sm">
                    <span class="text-[10px] font-black text-stone-400 uppercase tracking-widest block mb-1">Community Growth</span>
                    <h3 class="text-3xl font-black text-stone-900 dark:text-white">
                        {{ $customers->total() }} <span class="text-xs font-normal text-stone-500">Members</span>
                    </h3>
                </div>
            </div>

            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-[2.5rem] border border-stone-200 dark:border-stone-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-800/50 text-stone-400 dark:text-stone-500 text-[10px] font-black uppercase tracking-[0.2em]">
                                <th class="py-6 px-8">Member</th>
                                <th class="py-6 px-6">Email Address</th>
                                <th class="py-6 px-6 text-center">Loyalty (Orders)</th>
                                <th class="py-6 px-6 text-center">Active Streak</th>
                                <th class="py-6 px-6">Member Since</th>
                                <th class="py-6 px-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @forelse($customers as $user)
                                <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/30 transition-colors group">
                                    <td class="py-6 px-8">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-stone-100 dark:bg-stone-800 flex items-center justify-center border border-stone-200 dark:border-stone-700 font-serif italic text-xl text-stone-400">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-stone-900 dark:text-white text-base group-hover:text-amber-600 transition-colors">{{ $user->name }}</div>
                                                <div class="text-[9px] text-stone-400 font-black uppercase tracking-widest mt-0.5">ID #{{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6">
                                        <span class="text-stone-600 dark:text-stone-400 font-medium text-sm">{{ $user->email }}</span>
                                    </td>
                                    <td class="py-6 px-6 text-center">
                                        <span class="inline-block bg-amber-500/10 text-amber-600 py-1 px-4 rounded-full text-[10px] font-black uppercase tracking-widest">
                                            {{ $user->orders_count }} Orders
                                        </span>
                                    </td>
                                    <td class="py-6 px-6 text-center">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 rounded-full border border-amber-100 dark:border-amber-800/30">
                                            <svg class="w-3 h-3 {{ $user->streak_count > 1 ? 'text-amber-500 animate-pulse' : 'text-stone-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-1.513-1.155-2.452a10.11 10.11 0 00-.4-1.045zM10 17a1 1 0 100-2 1 1 0 000 2z" />
                                            </svg>
                                            <span class="text-[10px] font-black uppercase text-amber-700 dark:text-amber-500">{{ $user->streak_count ?? 0 }} Days</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6">
                                        <div class="text-stone-900 dark:text-white font-bold text-xs">{{ $user->created_at->format('M d, Y') }}</div>
                                        <div class="text-[8px] text-stone-400 font-black uppercase mt-0.5">Joined</div>
                                    </td>
                                    <td class="py-6 px-8 text-right">
                                        <a href="{{ route('admin.customers.show', $user->id) }}" class="inline-flex items-center gap-2 bg-stone-900 dark:bg-stone-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="font-serif italic text-3xl text-stone-300 dark:text-stone-700 mb-2">No customers found</div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-stone-400">Try adjusting your search filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($customers->hasPages())
                    <div class="p-8 border-t border-stone-100 dark:border-stone-800">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>