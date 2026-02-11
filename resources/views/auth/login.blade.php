<x-guest-layout>
    <div class="flex min-h-screen bg-stone-50 dark:bg-stone-950 transition-colors duration-500">
        
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-stone-950/60 backdrop-blur-[2px] flex flex-col justify-end p-16">
                <blockquote class="text-white max-w-lg">
                    <p class="text-4xl font-black italic tracking-tighter mb-4 leading-tight">"Coffee is a language in itself."</p>
                    <footer class="text-amber-500 text-xs font-black tracking-[0.4em] uppercase">— Miks Coffee</footer>
                </blockquote>
            </div>
        </div>

        <div class="flex flex-col justify-center w-full lg:w-1/2 px-8 lg:px-24 py-12 bg-stone-100 dark:bg-stone-900 border-l border-stone-100 dark:border-stone-800">
            <div class="w-full max-w-md mx-auto">
                
                <div class="mb-12">
                    <div class="h-20 w-20 rounded-[2rem] overflow-hidden mb-8 shadow-beige dark:shadow-connected border-2 border-stone-100 dark:border-stone-800 rotate-3 hover:rotate-0 transition-transform duration-500">
                        <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    
                    <h2 class="text-4xl font-black text-stone-900 dark:text-white tracking-tighter uppercase">Welcome back</h2>
                    <p class="text-stone-500 dark:text-stone-400 mt-2 text-sm font-medium">Please enter your details to sign in.</p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf 

                    <div>
                        <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-stone-500 dark:text-stone-400 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none placeholder-stone-400"
                            placeholder="name@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-stone-500 dark:text-stone-400 mb-2">Password</label>
                        <div class="relative group">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                                class="w-full px-5 py-4 rounded-2xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none pr-14 placeholder-stone-400"
                                placeholder="••••••••">
                            
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-amber-500 transition-colors p-2">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.563 3.029m5.858.908l3.59 3.59" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-stone-300 dark:border-stone-700 text-amber-500 shadow-sm focus:ring-amber-500/20 bg-stone-50 dark:bg-stone-950 transition-all">
                            <span class="ml-3 text-sm text-stone-600 dark:text-stone-400 font-bold group-hover:text-stone-900 dark:group-hover:text-white transition-colors">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-amber-600 hover:text-amber-700 font-black uppercase tracking-widest transition-all" href="{{ route('password.request') }}">
                                Forgot?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-stone-900 dark:bg-amber-500 text-white dark:text-stone-950 font-black uppercase tracking-[0.2em] py-5 rounded-[2rem] shadow-lg hover:shadow-amber-500/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        Secure Sign In
                    </button>
                </form>

                <div class="mt-10 text-center border-t border-stone-100 dark:border-stone-800 pt-8">
                    <p class="text-stone-500 dark:text-stone-400 text-sm font-medium">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-black uppercase tracking-widest ml-2 underline decoration-2 underline-offset-4">
                            Register
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        setInterval(function() {
            fetch('/refresh-csrf').catch(error => {});
        }, 300000);
    </script>
</x-guest-layout>