<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-stone-900 dark:text-white uppercase tracking-tight italic">
            Settings & Privacy
        </h2>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            
            {{-- Session Security --}}
            <div class="bg-white dark:bg-stone-900 rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-10">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic">Login Activity</h3>
                        <p class="text-[10px] text-stone-500 font-bold uppercase tracking-widest">Recent sessions across your devices</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($loginHistory as $entry)
                        <div class="flex items-center justify-between p-6 bg-stone-50 dark:bg-stone-950/50 rounded-3xl border border-stone-100 dark:border-stone-800">
                            <div class="flex items-center gap-5">
                                <div class="w-10 h-10 rounded-full bg-stone-200 dark:bg-stone-800 flex items-center justify-center text-stone-500">
                                    @if(str_contains(strtolower($entry->user_agent), 'mobile'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-black text-stone-900 dark:text-white uppercase tracking-tight">{{ $entry->ip_address }}</p>
                                    <p class="text-[9px] text-stone-400 font-bold uppercase truncate max-w-[200px]">{{ $entry->user_agent }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-stone-900 dark:text-white uppercase italic">{{ $entry->login_at->format('M d, Y') }}</p>
                                <p class="text-[9px] text-stone-400 font-bold uppercase tracking-tighter">{{ $entry->login_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Account Data --}}
            <div class="bg-stone-900 rounded-[3rem] p-10 border border-stone-800 shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white uppercase italic tracking-tighter mb-4">Privacy Controls</h3>
                    <p class="text-xs text-stone-400 font-medium italic leading-relaxed mb-8 uppercase tracking-tight">
                        Download your records or view the human-readable compliance report.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('profile.data_report') }}" class="py-4 bg-white text-stone-900 rounded-2xl font-black uppercase tracking-widest text-[9px] text-center hover:bg-amber-500 hover:text-white transition-all">View Report</a>
                        <form action="{{ route('profile.export') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 border border-stone-700 text-white rounded-2xl font-black uppercase tracking-widest text-[9px] hover:border-amber-500 transition-all">Export JSON</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>