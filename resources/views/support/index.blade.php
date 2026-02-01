<x-app-layout>
    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header with Live Status --}}
            <div class="text-center mb-10 sm:mb-16">
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-1.5 bg-stone-100 dark:bg-stone-900 rounded-full border border-stone-200 dark:border-stone-800 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[9px] font-black text-stone-600 dark:text-stone-400 uppercase tracking-[0.2em] italic">Terminal Active</span>
                </div>
                <h2 class="text-4xl sm:text-6xl font-black text-stone-900 dark:text-white tracking-tighter uppercase italic leading-none mb-4">
                    How can we <br class="xs:hidden"> assist?
                </h2>
                <p class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-[0.4em] max-w-xs mx-auto leading-relaxed">
                    Direct access to the <span class="text-amber-600">Mik's Coffee</span> Concierge Desk
                </p>
            </div>

            {{-- Success Notification --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
                     class="mb-8 p-6 bg-stone-900 border border-stone-800 rounded-[2rem] shadow-2xl flex items-center gap-4 transition-all">
                    <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-white">Transmission Received</p>
                        <p class="text-xs text-stone-400 italic">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Support Terminal Card --}}
            <div x-data="{ loading: false }" class="bg-white dark:bg-stone-900 p-8 sm:p-14 rounded-[3rem] border border-stone-100 dark:border-stone-800 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-stone-50 dark:bg-stone-800/20 rounded-full blur-[80px] -mr-20 -mt-20"></div>

                <form action="{{ route('support.send') }}" method="POST" @submit="loading = true" class="space-y-8 relative z-10">
                    @csrf
                    
                    @guest
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black uppercase tracking-[0.3em] text-stone-400 ml-2">Authorized Name</label>
                                <input type="text" name="guest_name" value="{{ old('guest_name') }}" required 
                                       class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-amber-500 dark:text-white transition-all shadow-inner"
                                       placeholder="Full Identity">
                                @error('guest_name') <p class="text-[8px] text-rose-500 font-black uppercase ml-2 tracking-widest">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[9px] font-black uppercase tracking-[0.3em] text-stone-400 ml-2">Return Address</label>
                                <input type="email" name="guest_email" value="{{ old('guest_email') }}" required 
                                       class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-amber-500 dark:text-white transition-all shadow-inner"
                                       placeholder="email@example.com">
                                @error('guest_email') <p class="text-[8px] text-rose-500 font-black uppercase ml-2 tracking-widest">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endguest

                    <div class="space-y-2">
                        <label class="block text-[9px] font-black uppercase tracking-[0.3em] text-stone-400 ml-2">Inquiry Classification</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required 
                               class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-amber-500 dark:text-white transition-all shadow-inner"
                               placeholder="Subject of Request">
                        @error('subject') <p class="text-[8px] text-rose-500 font-black uppercase ml-2 tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-black uppercase tracking-[0.3em] text-stone-400 ml-2">Transmission Body</label>
                        <textarea name="message" rows="5" required 
                                  class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-3xl px-6 py-5 text-sm focus:ring-2 focus:ring-amber-500 dark:text-white italic transition-all shadow-inner" 
                                  placeholder="Describe your request in detail for our concierge team...">{{ old('message') }}</textarea>
                        @error('message') <p class="text-[8px] text-rose-500 font-black uppercase ml-2 tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-5 sm:py-6 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-[2.5rem] font-black uppercase tracking-[0.3em] text-[10px] shadow-2xl transition-all hover:bg-amber-600 dark:hover:bg-amber-500 hover:scale-[1.01] active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3 overflow-hidden group"
                            :disabled="loading">
                        <span x-show="!loading" class="flex items-center gap-3">
                            Authorize Transmission
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                        <span x-show="loading" class="flex items-center gap-3" x-cloak>
                            <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Syncing...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Footer Note --}}
            <div class="mt-12 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-stone-400 italic">
                    All transmissions are logged and monitored.
                </p>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .shadow-inner {
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }
        .dark .shadow-inner {
            box-shadow: inset 0 2px 10px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</x-app-layout>