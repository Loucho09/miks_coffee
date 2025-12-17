<x-guest-layout>
    <div class="flex min-h-screen bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-stone-900/40 flex items-center justify-center">
                <div class="text-white px-12 text-center">
                    <h1 class="text-5xl font-bold mb-6 tracking-tight font-sans">Welcome Back.</h1>
                    <p class="text-xl font-light opacity-90 text-stone-100">Your daily dose of happiness is just one click away.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-center w-full lg:w-1/2 px-8 lg:px-24 py-12 bg-white dark:bg-stone-900">
            
            <div class="w-full max-w-md mx-auto">
                <div class="mb-10 text-center lg:text-left">
                    <div class="flex justify-center lg:justify-start mb-4">
                        <span class="text-5xl">☕</span>
                    </div>
                    <h2 class="text-3xl font-bold text-stone-900 dark:text-white tracking-tight">Sign In</h2>
                    <p class="text-stone-500 dark:text-stone-400 mt-2">Log in to access your rewards and menu.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf  <div class="mb-5">
                        <label for="email" class="block text-sm font-bold text-stone-700 dark:text-stone-300 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                            class="w-full px-4 py-3 rounded-lg border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white focus:border-amber-600 focus:ring-amber-600 transition shadow-sm outline-none"
                            placeholder="you@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-bold text-stone-700 dark:text-stone-300 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-lg border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 text-stone-900 dark:text-white focus:border-amber-600 focus:ring-amber-600 transition shadow-sm outline-none"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-300 dark:border-stone-600 text-amber-600 shadow-sm focus:ring-amber-600 bg-stone-100 dark:bg-stone-800">
                            <span class="ml-2 text-sm text-stone-600 dark:text-stone-400 font-medium">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-amber-600 hover:text-amber-700 font-bold hover:underline transition" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                        {{ __('Log In') }}
                    </button>

                    <div class="mt-8 text-center border-t border-stone-100 dark:border-stone-800 pt-6">
                        <p class="text-stone-500 dark:text-stone-400 text-sm">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-bold hover:underline ml-1">
                                Sign up now
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>