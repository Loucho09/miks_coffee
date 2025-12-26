<x-app-layout>
    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen transition-colors duration-500">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Page Header --}}
            <div class="text-center mb-12">
                <span class="text-amber-600 font-black uppercase tracking-[0.4em] text-[10px] mb-3 block">Concierge</span>
                <h2 class="font-black text-4xl sm:text-5xl text-stone-900 dark:text-white uppercase tracking-tighter italic leading-none">How can we help?</h2>
                <p class="text-stone-500 dark:text-stone-400 mt-4 font-medium italic text-sm sm:text-base">
                    Whether it's a brew inquiry or feedback on your experience, <br class="hidden sm:block"> our team is here to brew up a solution.
                </p>
            </div>

            {{-- Success Notification --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-8 p-5 bg-amber-500/10 border border-amber-500/20 rounded-[2rem] text-amber-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-4 transition-all shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Support Form Card --}}
            <div class="bg-white dark:bg-stone-900 rounded-[3rem] p-8 sm:p-12 border border-stone-200 dark:border-stone-800 shadow-2xl relative overflow-hidden group">
                {{-- Decorative Element --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-stone-50 dark:bg-stone-950 rounded-full blur-3xl -mr-16 -mt-16 opacity-50"></div>

                <form action="{{ route('support.send') }}" method="POST" class="space-y-8 relative z-10">
                    @csrf
                    
                    {{-- Identity Section for Guests --}}
                    @guest
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-stone-400 px-2">Your Name</label>
                                <input type="text" name="guest_name" required 
                                    class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all placeholder:text-stone-400/50"
                                    placeholder="Enter your name">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-stone-400 px-2">Email Address</label>
                                <input type="email" name="guest_email" required 
                                    class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all placeholder:text-stone-400/50"
                                    placeholder="Enter your email">
                            </div>
                        </div>
                    @endguest

                    {{-- Subject Section --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-stone-400 px-2">Subject</label>
                        <input type="text" name="subject" required 
                            class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-2xl px-6 py-4 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all placeholder:text-stone-400/50"
                            placeholder="What is this regarding? (e.g. Order #123, Loyalty Points)">
                    </div>

                    {{-- Message Section --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-stone-400 px-2">Message Detail</label>
                        <textarea name="message" rows="6" required 
                            class="w-full bg-stone-50 dark:bg-stone-950 border-none rounded-[2rem] px-6 py-5 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 italic transition-all placeholder:text-stone-400/50 text-sm sm:text-base"
                            placeholder="Tell us more about your request..."></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full py-5 bg-stone-900 dark:bg-stone-50 text-white dark:text-stone-950 rounded-[2rem] font-black uppercase tracking-[0.2em] text-[11px] sm:text-xs hover:bg-amber-600 dark:hover:bg-amber-500 transition-all shadow-xl active:scale-[0.98] group/btn">
                            <span class="flex items-center justify-center gap-3">
                                Send Inquiry
                                <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Info --}}
            <div class="mt-12 text-center opacity-40">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-stone-500">
                    Mik's Coffee Shop • Est. 2025 • Freshly Brewed Support
                </p>
            </div>
        </div>
    </div>
</x-app-layout>