<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    Support Management
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium">
                    Manage and resolve customer inquiries.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-600 font-bold text-xs uppercase tracking-widest">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50 dark:bg-stone-800/50 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="p-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Customer Details</th>
                            <th class="p-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Issue & Message</th>
                            <th class="p-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Status</th>
                            <th class="p-6 text-[10px] font-black uppercase tracking-widest text-stone-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($tickets as $ticket)
                        <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/30 transition-colors">
                            <td class="p-6">
                                <div class="font-bold text-stone-900 dark:text-white">{{ $ticket->user->name }}</div>
                                <div class="text-[10px] text-amber-600 font-black tracking-tighter uppercase">{{ $ticket->user->email }}</div>
                            </td>
                            <td class="p-6">
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest mb-2 inline-block">
                                    {{ $ticket->subject }}
                                </span>
                                <p class="text-sm text-stone-600 dark:text-stone-400 font-light leading-relaxed">
                                    {{ $ticket->message }}
                                </p>
                                <div class="text-[9px] text-stone-400 mt-2 uppercase tracking-widest">
                                    Received: {{ $ticket->created_at->format('M d, Y h:i A') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @if($ticket->status === 'open')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 text-[9px] font-black uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                        Open
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-[9px] font-black uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Resolved
                                    </span>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                @if($ticket->status === 'open')
                                <form method="POST" action="{{ route('admin.support.resolve', $ticket->id) }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-stone-900 dark:bg-stone-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-colors">
                                        Mark Resolved
                                    </button>
                                </form>
                                @else
                                <span class="text-[10px] font-black text-stone-300 dark:text-stone-700 uppercase tracking-widest">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <div class="text-stone-300 dark:text-stone-700 font-serif italic text-4xl mb-2">No Active Requests</div>
                                <p class="text-stone-400 text-xs font-black uppercase tracking-widest">All customers are currently satisfied!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($tickets->hasPages())
                    <div class="p-6 border-t border-stone-100 dark:border-stone-800">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>