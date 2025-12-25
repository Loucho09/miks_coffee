<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    {{ __('Support Requests') }}
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium italic font-serif">
                    Manage and respond to customer inquiries.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-2 mb-8 overflow-x-auto pb-2 no-scrollbar">
                <a href="{{ route('admin.support.admin_index') }}" 
                   class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ !request('status') ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800' }}">
                    All Tickets
                </a>
                <a href="{{ route('admin.support.admin_index', ['status' => 'pending']) }}" 
                   class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800' }}">
                    Pending
                </a>
                <a href="{{ route('admin.support.admin_index', ['status' => 'resolved']) }}" 
                   class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ request('status') === 'resolved' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/20' : 'bg-white dark:bg-stone-900 text-stone-500 border border-stone-200 dark:border-stone-800' }}">
                    Resolved
                </a>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-600 font-bold text-[10px] uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 overflow-hidden shadow-sm rounded-[2.5rem] border border-stone-200 dark:border-stone-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 dark:bg-stone-800/50 text-stone-400 dark:text-stone-500 text-[10px] font-black uppercase tracking-[0.2em]">
                                <th class="p-8">User Details</th>
                                <th class="p-8">Message Details</th>
                                <th class="p-8 text-center">Status</th>
                                <th class="p-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/30 transition-colors">
                                <td class="p-8">
                                    <div class="font-bold text-stone-900 dark:text-white">
                                        {{ $ticket->user?->name ?? 'Guest/Deleted User' }}
                                    </div>
                                    <div class="text-[10px] text-amber-600 font-black tracking-tighter uppercase">
                                        {{ $ticket->user?->email ?? 'No associated account' }}
                                    </div>
                                </td>
                                <td class="p-8">
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest mb-2 inline-block">
                                        {{ $ticket->subject }}
                                    </span>
                                    <p class="text-sm text-stone-600 dark:text-stone-400 font-light leading-relaxed max-w-md">
                                        {{ $ticket->message }}
                                    </p>
                                    <div class="text-[9px] text-stone-400 mt-2 uppercase tracking-widest">
                                        Submitted: {{ $ticket->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </td>
                                
                                <td class="p-8 text-center">
                                    @if($ticket->status === 'resolved')
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-green-500/10 text-green-600 text-[8px] font-black uppercase tracking-widest">
                                            <span class="w-1 h-1 rounded-full bg-green-500"></span>
                                            Resolved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-amber-500/10 text-amber-600 text-[8px] font-black uppercase tracking-widest animate-pulse">
                                            <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="p-8 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($ticket->status !== 'resolved')
                                            <form action="{{ route('admin.support.resolve', $ticket->id) }}" method="POST" onsubmit="return confirm('Mark this request as resolved?');">
                                                @csrf
                                                <button type="submit" class="bg-stone-900 dark:bg-amber-600 hover:bg-amber-700 text-white font-black uppercase text-[9px] tracking-widest py-2.5 px-6 rounded-full transition transform hover:scale-105 shadow-lg shadow-amber-600/10">
                                                    Resolve
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-stone-400 text-[9px] font-black uppercase italic tracking-widest">Archive</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-20 text-center">
                                    <div class="text-stone-300 dark:text-stone-700 mb-4">
                                        <svg class="w-12 h-12 mx-auto opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-stone-400 dark:text-stone-500 text-sm font-medium italic">No support requests in this category.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="p-8 border-t border-stone-100 dark:border-stone-800">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>