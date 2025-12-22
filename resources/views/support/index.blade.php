<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-stone-900 dark:text-white uppercase tracking-tight">
                    Customer Support
                </h2>
                <p class="text-xs md:text-sm text-stone-500 dark:text-stone-400 mt-1 font-medium">
                    @auth
                        How can we help you today, {{ Auth::user()->name }}?
                    @else
                        How can we help you today?
                    @endauth
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 dark:bg-stone-950 min-h-screen">
        <div class="max-w-3xl mx-auto px-6">
            
            @if(session('success'))
                <div class="mb-8 p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-3xl flex items-center gap-4 text-green-700 dark:text-green-400 font-bold uppercase text-[10px] tracking-widest">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-stone-900 rounded-[2.5rem] border border-stone-200 dark:border-stone-800 shadow-xl p-8 md:p-12">
                <form action="{{ route('support.send') }}" method="POST" class="space-y-8">
                    @csrf

                    @guest
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-600 mb-3">Your Name</label>
                                <input type="text" name="guest_name" required 
                                    class="w-full px-6 py-4 rounded-2xl bg-stone-50 dark:bg-stone-950 border-none shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-600 mb-3">Email Address</label>
                                <input type="email" name="guest_email" required 
                                    class="w-full px-6 py-4 rounded-2xl bg-stone-50 dark:bg-stone-950 border-none shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white transition-all">
                            </div>
                        </div>
                    @endguest

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-600 mb-3">Subject</label>
                        <select name="subject" required class="w-full px-6 py-4 rounded-2xl bg-stone-50 dark:bg-stone-950 border-none shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white transition-all appearance-none">
                            <option value="Order Inquiry">Order Inquiry</option>
                            <option value="Reward Points Issue">Reward Points Issue</option>
                            <option value="Feedback">General Feedback</option>
                            <option value="Technical Support">Technical Support</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-3">Describe your concern</label>
                        <textarea name="message" rows="6" required 
                            class="w-full px-6 py-4 rounded-[2rem] bg-stone-50 dark:bg-stone-950 border-none shadow-inner focus:ring-2 focus:ring-amber-500 text-stone-900 dark:text-white transition-all resize-none"
                            placeholder="Tell us what's on your mind..."></textarea>
                    </div>

                    <button type="submit" 
                        class="w-full py-5 bg-stone-900 dark:bg-amber-600 text-white rounded-2xl font-black uppercase text-xs tracking-[0.3em] hover:bg-amber-700 transition transform active:scale-[0.98] shadow-xl shadow-amber-600/10">
                        Send Request
                    </button>
                </form>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 px-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-600 border border-amber-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-stone-400 tracking-widest">Email Us</p>
                        <p class="text-sm font-bold text-stone-800 dark:text-white">support@mikscoffee.com</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600 border border-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-stone-400 tracking-widest">Typical Response</p>
                        <p class="text-sm font-bold text-stone-800 dark:text-white">Within 24 Hours</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>