<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    Customer Support
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium">
                    How can we help you today, {{ Auth::user()->name }}?
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">
            
            @if(session('success'))
                <div class="mb-8 p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-3xl flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-green-800 dark:text-green-200 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 p-8 md:p-12 rounded-[3rem] border border-stone-200 dark:border-stone-800 shadow-sm">
                <form method="POST" action="{{ route('support.send') }}" class="space-y-8">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-amber-600 mb-3">Reason for Inquiry</label>
                        <select name="subject" required 
                                class="w-full px-6 py-4 rounded-2xl border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all outline-none">
                            <option value="" disabled selected>Select a topic...</option>
                            <option value="Order Issue">Order Issue</option>
                            <option value="Rewards/Points Inquiry">Rewards/Points Inquiry</option>
                            <option value="Account Help">Account Help</option>
                            <option value="Feedback/Suggestion">Feedback/Suggestion</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-amber-600 mb-3">Your Message</label>
                        <textarea name="message" rows="6" required 
                                  placeholder="Tell us more about your request..."
                                  class="w-full px-6 py-4 rounded-3xl border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition-all outline-none resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-5 bg-stone-900 dark:bg-amber-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-amber-700 transition-all transform hover:-translate-y-1 shadow-lg shadow-amber-600/10">
                            Send Support Request
                        </button>
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-stone-100 dark:border-stone-800 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-1">Average Response Time</p>
                        <p class="text-sm font-bold text-stone-700 dark:text-stone-300">Under 24 Hours</p>
                    </div>
                    <div class="flex gap-4">
                        <span class="font-serif italic text-4xl text-stone-100 dark:text-stone-800 select-none">M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>