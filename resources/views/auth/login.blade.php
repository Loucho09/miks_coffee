<x-guest-layout>
    <div class="flex min-h-screen bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-[2px] flex flex-col justify-end p-16">
                <blockquote class="text-white max-w-lg">
                    <p class="text-3xl font-bold font-serif mb-4 leading-tight">"Coffee is a language in itself."</p>
                    <footer class="text-stone-300 text-sm font-medium tracking-widest uppercase">— Miks Coffee</footer>
                </blockquote>
            </div>
        </div>

        <div class="flex flex-col justify-center w-full lg:w-1/2 px-8 lg:px-24 py-12 bg-white dark:bg-stone-900">
            <div class="w-full max-w-md mx-auto">
                
                <div class="mb-10">
                    <div class="h-16 w-16 rounded-full overflow-hidden mb-6 shadow-lg border-2 border-stone-100 dark:border-stone-800">
                        <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    
                    <h2 class="text-3xl font-bold text-stone-900 dark:text-white tracking-tight">Welcome back</h2>
                    <p class="text-stone-500 dark:text-stone-400 mt-2 text-sm">Please enter your details to sign in.</p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf 

                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700 dark:text-stone-300 mb-1.5">Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                            class="w-full px-4 py-3 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition outline-none"
                            placeholder="name@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-semibold text-stone-700 dark:text-stone-300 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition outline-none pr-12"
                                placeholder="Enter your password">
                            
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 transition p-1">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.563 3.029m5.858.908l3.59 3.59" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-300 dark:border-stone-700 text-amber-600 shadow-sm focus:ring-amber-500 bg-stone-50 dark:bg-stone-800">
                            <span class="ml-2 text-sm text-stone-600 dark:text-stone-400 font-medium">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-amber-600 hover:text-amber-700 font-semibold hover:underline transition" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-stone-900 dark:bg-white text-white dark:text-stone-900 font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.01] transition transform">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-stone-100 dark:border-stone-800 pt-6">
                    <p class="text-stone-500 dark:text-stone-400 text-sm">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-bold hover:underline ml-1">
                            Create account
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>