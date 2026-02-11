<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-stone-50 dark:bg-stone-950 px-4">
        <div class="w-full max-w-md bg-white dark:bg-stone-900 rounded-[3rem] p-10 shadow-2xl border border-stone-200 dark:border-stone-800">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500/10 mb-6">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-stone-900 dark:text-white uppercase italic tracking-tighter leading-none mb-2">Check Your Email</h2>
                <p class="text-stone-500 dark:text-stone-400 text-sm italic">We sent a 6-digit verification code to your Gmail.</p>
            </div>

            <form method="POST" action="{{ route('verification.code.verify') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="code" class="block text-[10px] font-black text-amber-600 uppercase tracking-[0.3em] mb-3 ml-1">Verification Code</label>
                    <input id="code" type="text" name="code" required autofocus 
                           placeholder="000000"
                           maxlength="6"
                           class="w-full px-6 py-4 bg-stone-100 dark:bg-stone-800 border-none rounded-2xl text-stone-900 dark:text-white text-center text-2xl font-black tracking-[0.5em] focus:ring-2 focus:ring-amber-500 transition-all placeholder-stone-400">
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <button type="submit" class="w-full py-4 bg-amber-600 hover:bg-amber-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-lg shadow-amber-600/20 transition-all active:scale-95">
                    Verify Account
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-stone-100 dark:border-stone-800 text-center">
                <p class="text-xs text-stone-500 mb-4 italic">Didn't receive the code?</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase text-stone-400 hover:text-amber-600 tracking-widest transition">
                        Use a different email
                    </button>
                </form>
            </div>
        </div>
        
        <p class="mt-8 text-stone-500 dark:text-stone-600 text-[10px] font-black uppercase tracking-[0.2em]">© 2026 Mik's Coffee Shop</p>
    </div>
</x-guest-layout>