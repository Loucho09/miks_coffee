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

    <div x-data="{ replyModal: false, selectedTicketId: null, customerName: '', originalMessage: '' }" class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm relative overflow-hidden group">
                    <p class="text-[10px] font-black text-stone-400 uppercase tracking-[0.3em] mb-2 relative z-10">Total Volume</p>
                    <h4 class="text-4xl font-black text-stone-900 dark:text-white relative z-10">{{ $tickets->total() }}</h4>
                </div>

                <div class="bg-white dark:bg-stone-900 p-8 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-sm relative overflow-hidden group">
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em] mb-2 relative z-10">Awaiting Response</p>
                    <h4 class="text-4xl font-black text-stone-900 dark:text-white relative z-10">
                        {{ $tickets->getCollection()->where('status', 'pending')->count() }}
                    </h4>
                </div>

                <div class="bg-stone-900 p-8 rounded-[2.5rem] shadow-2xl border border-stone-800 relative overflow-hidden group">
                    <p class="text-[10px] font-black text-stone-500 uppercase tracking-[0.3em] mb-2 relative z-10">Successfully Resolved</p>
                    <h4 class="text-4xl font-black text-white relative z-10">
                        {{ $tickets->getCollection()->where('status', 'resolved')->count() }}
                    </h4>
                </div>
            </div>

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
                                <td class="p-8 align-top">
                                    <div class="font-bold text-stone-900 dark:text-white">
                                        {{ $ticket->user?->name ?? ($ticket->guest_name ?? 'Guest') }}
                                    </div>
                                    <div class="text-[10px] text-amber-600 font-black tracking-tighter uppercase">
                                        {{ $ticket->user?->email ?? ($ticket->guest_email ?? 'No email') }}
                                    </div>
                                </td>
                                <td class="p-8 align-top">
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest mb-2 inline-block">
                                        {{ $ticket->subject }}
                                    </span>
                                    <p class="text-sm text-stone-600 dark:text-stone-400 font-light leading-relaxed max-w-md">
                                        {{ $ticket->message }}
                                    </p>
                                    
                                    @if($ticket->replies && $ticket->replies->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-stone-100 dark:border-stone-800 space-y-3">
                                            <p class="text-[8px] font-black text-stone-400 uppercase tracking-widest">Reply History</p>
                                            @foreach($ticket->replies as $reply)
                                                <div class="bg-stone-50 dark:bg-stone-950 p-3 rounded-xl border border-stone-100 dark:border-stone-800">
                                                    <p class="text-xs text-stone-600 dark:text-stone-400 italic">"{{ $reply->message }}"</p>
                                                    <div class="mt-1 text-[7px] font-black text-amber-600 uppercase">Admin • {{ $reply->created_at->format('M d, h:i A') }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="text-[9px] text-stone-400 mt-4 uppercase tracking-widest">
                                        Submitted: {{ $ticket->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </td>
                                
                                <td class="p-8 text-center align-top">
                                    @php
                                        $statusClasses = [
                                            'resolved' => 'bg-green-500/10 text-green-600',
                                            'replied' => 'bg-blue-500/10 text-blue-600',
                                            'pending' => 'bg-amber-500/10 text-amber-600 animate-pulse'
                                        ];
                                        $currentClass = $statusClasses[$ticket->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full {{ $currentClass }} text-[8px] font-black uppercase tracking-widest">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>

                                <td class="p-8 text-right align-top">
                                    <div class="flex flex-col items-end gap-2">
                                        @if($ticket->status !== 'resolved')
                                            <button @click="replyModal = true; selectedTicketId = {{ $ticket->id }}; customerName = '{{ addslashes($ticket->user?->name ?? ($ticket->guest_name ?? 'Guest')) }}'; originalMessage = '{{ addslashes($ticket->message) }}'" 
                                                class="bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 font-black uppercase text-[9px] tracking-widest py-2 px-6 rounded-full transition transform hover:scale-105 shadow-md w-32 text-center">
                                                Reply
                                            </button>
                                            
                                            <form action="{{ route('admin.support.resolve', $ticket->id) }}" method="POST" onsubmit="return confirm('Mark this request as resolved?');">
                                                @csrf
                                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-black uppercase text-[9px] tracking-widest py-2 px-6 rounded-full transition transform hover:scale-105 shadow-md w-32 text-center">
                                                    Resolve
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-stone-400 text-[9px] font-black uppercase italic tracking-widest pr-4">Archive</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-20 text-center">
                                    <p class="text-stone-400 dark:text-stone-500 text-sm font-medium italic">No support requests found.</p>
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

        <div x-show="replyModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4 bg-stone-950/80 backdrop-blur-md" x-cloak x-transition>
            <div class="bg-white dark:bg-stone-900 w-full max-w-lg rounded-[2.5rem] sm:rounded-[3.5rem] border border-stone-200 dark:border-stone-800 shadow-2xl overflow-hidden" @click.away="replyModal = false">
                <div class="p-8 sm:p-12">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-stone-900 dark:text-white font-black text-2xl uppercase tracking-tighter italic leading-none">Draft Reply</h3>
                            <p class="text-amber-600 text-[10px] font-bold uppercase tracking-[0.2em] mt-3 italic" x-text="'Replying to ' + customerName"></p>
                        </div>
                        <button @click="replyModal = false" class="p-2 text-stone-400 hover:text-stone-900 dark:hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="mb-8 p-4 bg-stone-50 dark:bg-stone-950 rounded-2xl border-l-2 border-stone-200 dark:border-stone-800">
                        <p class="text-[8px] font-black uppercase text-stone-400 mb-2">Original Message</p>
                        <p class="text-xs text-stone-500 italic line-clamp-3" x-text="originalMessage"></p>
                    </div>

                    <form :action="'{{ url('admin/support-requests') }}/' + selectedTicketId + '/reply'" method="POST">
                        @csrf
                        <div class="mb-8">
                            <label class="block text-[8px] font-black uppercase tracking-[0.4em] text-stone-500 mb-4 px-2 leading-none">Admin response</label>
                            <textarea name="message" rows="5" required 
                                class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl sm:rounded-[2rem] px-6 py-5 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all placeholder:text-stone-400/50 text-sm font-medium italic" 
                                placeholder="Write your response..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-5 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-900 rounded-[2rem] font-black uppercase tracking-[0.2em] hover:bg-amber-600 transition-all shadow-xl text-[10px]">Send Reply & Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>