<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl md:text-2xl text-stone-900 dark:text-white uppercase tracking-tight italic">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8">
            
            {{-- Profile Information Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-10 transition-all hover:shadow-2xl">
                <div class="max-w-full md:max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Security / Password Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-10 transition-all hover:shadow-2xl">
                <div class="max-w-full md:max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Login Activity Section --}}
            <div class="bg-white dark:bg-stone-900 rounded-[2rem] md:rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-xl p-6 md:p-10">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-5 mb-8 md:mb-10">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 border border-amber-500/20 shrink-0">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-black text-stone-900 dark:text-white uppercase tracking-tighter italic">Login Activity</h3>
                        <p class="text-[9px] md:text-[10px] text-stone-500 font-bold uppercase tracking-widest leading-none">Security monitoring across your devices</p>
                    </div>
                </div>

                <div class="space-y-3 md:space-y-4">
                    @forelse($loginHistory as $entry)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 md:p-6 bg-stone-50 dark:bg-stone-950/50 rounded-2xl md:rounded-3xl border border-stone-100 dark:border-stone-800 group hover:border-amber-500/30 transition-all gap-4 sm:gap-0">
                            <div class="flex items-center gap-4 md:gap-5 w-full sm:w-auto">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700 flex items-center justify-center text-stone-500 group-hover:text-amber-500 transition-colors shrink-0">
                                    @if(str_contains(strtolower($entry->user_agent), 'mobile'))
                                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-stone-900 dark:text-white uppercase tracking-tight">{{ $entry->ip_address }}</p>
                                    <p class="text-[9px] text-stone-400 font-bold uppercase truncate max-w-[150px] xs:max-w-[200px] sm:max-w-[180px] md:max-w-xs">{{ $entry->user_agent }}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center w-full sm:w-auto border-t sm:border-t-0 border-stone-200 dark:border-stone-800 pt-3 sm:pt-0">
                                <span class="px-2 py-0.5 md:px-3 md:py-1 bg-stone-200 dark:bg-stone-800 rounded-full text-[8px] md:text-[9px] font-black text-stone-600 dark:text-stone-400 uppercase tracking-tighter sm:mb-1">Success</span>
                                <div class="flex flex-col items-end">
                                    <p class="text-[9px] md:text-[10px] font-black text-stone-900 dark:text-white uppercase italic leading-none">{{ $entry->login_at->format('M d, Y') }}</p>
                                    <p class="text-[8px] md:text-[9px] text-stone-400 font-bold uppercase tracking-tighter">{{ $entry->login_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 md:py-10 border-2 border-dashed border-stone-200 dark:border-stone-800 rounded-2xl md:rounded-3xl">
                            <p class="text-[10px] md:text-xs font-bold text-stone-400 uppercase italic">No login activity recorded</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Privacy Controls Section --}}
            <div class="bg-stone-900 rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 border border-stone-800 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-4 -right-4 md:top-0 md:right-0 p-4 md:p-8 opacity-10">
                    <svg class="w-20 h-20 md:w-32 md:h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.47 4.34-2.98 8.19-7 9.41V12H5V6.3l7-3.11v8.8z"/></svg>
                </div>
                
                <div class="relative z-10">
                    <h3 class="text-xl md:text-2xl font-black text-white uppercase italic tracking-tighter mb-3 md:mb-4">Privacy & Data</h3>
                    <p class="text-[10px] md:text-xs text-stone-400 font-medium italic leading-relaxed mb-8 md:mb-10 uppercase tracking-tight max-w-full md:max-w-md">
                        Manage your digital footprint. Download your activity records or view the human-readable compliance report.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <a href="{{ route('profile.data_report') }}" class="py-4 md:py-5 bg-white text-stone-900 rounded-xl md:rounded-2xl font-black uppercase tracking-widest text-[9px] md:text-[10px] text-center hover:bg-amber-500 hover:text-white transition-all transform hover:-translate-y-1">View Data Report</a>
                        <form action="{{ route('profile.export') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 md:py-5 border border-stone-700 text-white rounded-xl md:rounded-2xl font-black uppercase tracking-widest text-[9px] md:text-[10px] hover:border-amber-500 hover:bg-amber-500/5 transition-all transform hover:-translate-y-1">Export JSON Archive</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Danger Zone Section (With Integrated Confirmation) --}}
            <div class="bg-red-50/30 dark:bg-red-950/10 rounded-[2rem] md:rounded-[3rem] border border-red-100 dark:border-red-900/30 p-6 md:p-10 shadow-lg" x-data="{ confirming: false }">
                <div class="max-w-full md:max-w-xl">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-2xl text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-red-600 dark:text-red-500 uppercase tracking-tighter italic">Danger Zone</h3>
                    </div>

                    <div x-show="!confirming" x-transition>
                        <p class="text-xs md:text-sm text-stone-600 dark:text-stone-400 font-medium leading-relaxed mb-6 uppercase tracking-tight">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data you wish to retain before proceeding.
                        </p>
                        <button type="button" @click="confirming = true" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all transform hover:scale-105 active:scale-95 shadow-md">
                            Delete Account
                        </button>
                    </div>

                    <div x-show="confirming" x-transition x-cloak class="mt-4">
                        <p class="text-[10px] md:text-xs text-red-600 font-black uppercase tracking-widest mb-4 italic">
                            Verification Required: Please enter your password to confirm permanent deletion.
                        </p>
                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                            @csrf
                            @method('delete')
                            <div>
                                <input id="password" name="password" type="password" placeholder="ENTER PASSWORD" class="w-full bg-white dark:bg-stone-950 border border-red-200 dark:border-red-900/50 rounded-xl px-5 py-4 text-xs font-bold text-stone-900 dark:text-white uppercase tracking-widest focus:ring-red-500 focus:border-red-500 transition-all shadow-inner" required />
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[10px]" />
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="submit" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] transition-all">
                                    Finalize Deletion
                                </button>
                                <button type="button" @click="confirming = false" class="px-6 py-4 border border-stone-300 dark:border-stone-700 text-stone-600 dark:text-stone-400 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>