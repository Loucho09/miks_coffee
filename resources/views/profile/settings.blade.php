<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-1.5 h-8 bg-amber-600 rounded-full"></div>
            <h2 class="font-black text-xl md:text-2xl text-stone-900 dark:text-white uppercase tracking-tight italic">
                {{ __('Settings & Privacy') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-10">
            
            {{-- Session Security / Login Activity --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-12 transition-all hover:shadow-2xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-5 mb-8 md:mb-12">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-stone-900 dark:bg-stone-800 flex items-center justify-center text-amber-500 border border-stone-700 shrink-0 shadow-lg">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">Login Activity</h3>
                        <p class="text-[10px] text-stone-400 font-black uppercase tracking-[0.2em] mt-1">Recent sessions across your devices</p>
                    </div>
                </div>

                <div class="space-y-4 md:space-y-5">
                    @forelse($loginHistory as $entry)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 md:p-8 bg-stone-50 dark:bg-stone-950/50 rounded-2xl md:rounded-[2rem] border border-stone-100 dark:border-stone-800 group hover:border-amber-500/30 transition-all gap-4 sm:gap-0">
                            <div class="flex items-center gap-5 w-full sm:w-auto">
                                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700 flex items-center justify-center text-stone-400 group-hover:text-amber-500 group-hover:border-amber-500/20 transition-all shrink-0 shadow-sm">
                                    @if(str_contains(strtolower($entry->user_agent), 'mobile'))
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-base font-black text-stone-900 dark:text-white uppercase tracking-tight leading-tight">{{ $entry->ip_address }}</p>
                                    <p class="text-[9px] text-stone-400 font-bold uppercase truncate max-w-[200px] md:max-w-xs tracking-wider mt-1">{{ $entry->user_agent }}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-auto border-t sm:border-t-0 border-stone-200 dark:border-stone-800 pt-4 sm:pt-0">
                                <span class="px-3 py-1 bg-stone-900 dark:bg-stone-800 rounded-lg text-[9px] font-black text-amber-500 uppercase tracking-widest sm:mb-2 shadow-sm">Verified</span>
                                <div class="flex flex-col items-end">
                                    <p class="text-[10px] md:text-xs font-black text-stone-900 dark:text-white uppercase italic leading-none tracking-tighter">{{ $entry->login_at->format('M d, Y') }}</p>
                                    <p class="text-[9px] text-stone-400 font-black uppercase tracking-[0.2em] mt-1">{{ $entry->login_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 md:py-16 border-2 border-dashed border-stone-200 dark:border-stone-800 rounded-[2rem] md:rounded-[3rem] bg-stone-50/50">
                            <p class="text-xs font-black text-stone-400 uppercase italic tracking-[0.3em]">No login activity recorded</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Privacy Controls / Account Data --}}
            <div class="bg-stone-900 rounded-[2rem] md:rounded-[3rem] p-8 md:p-14 border border-stone-800 shadow-2xl relative overflow-hidden group">
                {{-- Decorative Background Shield --}}
                <div class="absolute -top-10 -right-10 opacity-10 group-hover:scale-110 transition-transform duration-700 text-white select-none pointer-events-none">
                    <svg class="w-48 h-48 md:w-64 md:h-64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.47 4.34-2.98 8.19-7 9.41V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-2xl md:text-3xl font-black text-white uppercase italic tracking-tighter">Privacy Controls</h3>
                    </div>
                    <p class="text-xs md:text-sm text-stone-400 font-medium italic leading-relaxed mb-10 uppercase tracking-tight max-w-full md:max-w-lg">
                        Manage your digital footprint. Download your activity records or view the human-readable compliance report. We ensure all data is handled under strict roasting protocols.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('profile.data_report') }}" class="py-5 bg-white text-stone-900 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] text-center hover:bg-amber-500 hover:text-white transition-all transform hover:-translate-y-1 shadow-lg shadow-white/5">
                            View Data Report
                        </a>
                        <form action="{{ route('profile.export') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-5 border border-stone-700 text-stone-300 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:border-amber-500 hover:bg-amber-500/5 transition-all transform hover:-translate-y-1">
                                Export JSON Archive
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>