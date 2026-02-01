<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-1.5 h-8 bg-amber-600 rounded-full"></div>
            <h2 class="font-black text-xl md:text-2xl text-stone-900 dark:text-white uppercase tracking-tight italic">
                {{ __('Account Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-10">
            
            {{-- Profile Information Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-12 transition-all hover:shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div class="max-w-full md:max-w-xl relative z-10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Security / Password Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-12 transition-all hover:shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity text-amber-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                </div>
                <div class="max-w-full md:max-w-xl relative z-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Login Activity Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-12">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-5 mb-8 md:mb-10">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-stone-900 dark:bg-stone-800 flex items-center justify-center text-amber-500 border border-stone-700 shrink-0 shadow-lg">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">Login Activity</h3>
                        <p class="text-[10px] text-stone-400 font-black uppercase tracking-[0.2em] mt-1">Security monitoring across your devices</p>
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
                                    <p class="text-base font-black text-stone-900 dark:text-white uppercase tracking-tight">{{ $entry->ip_address }}</p>
                                    <p class="text-[9px] text-stone-400 font-bold uppercase truncate max-w-[200px] md:max-w-xs tracking-wider">{{ $entry->user_agent }}</p>
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
                            <div class="text-stone-300 dark:text-stone-700 mb-4 flex justify-center">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <p class="text-xs font-black text-stone-400 uppercase italic tracking-[0.3em]">Vault is empty: No activity records</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Privacy Controls Section --}}
            <div class="bg-stone-100 dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] p-8 md:p-14 border border-stone-800 shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 opacity-10 group-hover:scale-110 transition-transform duration-700 text-dandelion-500">
                    <svg class="w-48 h-48 md:w-64 md:h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.47 4.34-2.98 8.19-7 9.41V12H5V6.3l7-3.11v8.8z"/></svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-2xl md:text-3xl font-black dark:text-white uppercase italic tracking-tighter">Privacy & Data</h3>
                    </div>
                    <p class="text-xs md:text-sm text-stone-600 dark:text-stone-300 font-medium italic leading-relaxed mb-10 uppercase tracking-tight max-w-full md:max-w-lg">
                        Manage your digital footprint. Download your activity records or view the human-readable compliance report. We ensure all data is encrypted and handled under strict roasting protocols.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('profile.data_report') }}" class="py-5 bg-white text-stone-900 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] text-center hover:bg-amber-500 hover:text-white transition-all transform hover:-translate-y-1 shadow-lg shadow-white/5">View Data Report</a>
                        <form action="{{ route('profile.export') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-5 border border-stone-700 text-stone-600 dark:text-stone-200 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:border-amber-500 hover:bg-amber-500/5 transition-all transform hover:-translate-y-1">Export JSON Archive</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Danger Zone Section --}}
            <div class="bg-red-50/30 dark:bg-red-950/10 rounded-[2.5rem] md:rounded-[3.5rem] border-2 border-red-100/50 dark:border-red-900/20 p-8 md:p-14 shadow-inner" x-data="{ confirming: false }">
                <div class="max-w-full md:max-w-xl">
                    <div class="flex items-center gap-5 mb-6">
                        <div class="p-4 bg-red-600 rounded-2xl text-white shadow-lg shadow-red-600/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-red-600 dark:text-red-500 uppercase tracking-tighter italic leading-none">Danger Zone</h3>
                            <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mt-1">Irreversible account actions</p>
                        </div>
                    </div>

                    <div x-show="!confirming" x-transition>
                        <p class="text-xs md:text-sm text-stone-600 dark:text-stone-400 font-medium leading-relaxed mb-10 uppercase tracking-tight italic">
                            Once your account is deleted, all of its resources and data will be permanently deleted. This action is terminal and cannot be undone by support staff.
                        </p>
                        <button type="button" @click="confirming = true" class="px-10 py-5 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition-all transform hover:scale-105 active:scale-95 shadow-xl shadow-red-600/20">
                            Delete Account
                        </button>
                    </div>

                    <div x-show="confirming" x-transition x-cloak class="mt-6">
                        <div class="p-6 bg-white dark:bg-stone-950 rounded-3xl border border-red-200 dark:border-red-900/40 shadow-xl">
                            <p class="text-[10px] md:text-xs text-red-600 font-black uppercase tracking-[0.2em] mb-6 italic">
                                Final Warning: Enter your password to confirm total system removal.
                            </p>
                            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
                                @csrf
                                @method('delete')
                                <div>
                                    <input id="password" name="password" type="password" placeholder="PASSWORD" class="w-full bg-stone-50 dark:bg-stone-900 border-2 border-stone-200 dark:border-stone-800 rounded-2xl px-6 py-5 text-xs font-bold text-stone-900 dark:text-white uppercase tracking-widest focus:ring-red-500 focus:border-red-500 transition-all shadow-inner" required />
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[10px]" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="submit" class="flex-1 py-5 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-lg shadow-red-600/20">
                                        Confirm Deletion
                                    </button>
                                    <button type="button" @click="confirming = false" class="px-8 py-5 border-2 border-stone-200 dark:border-stone-800 text-stone-500 dark:text-stone-400 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>