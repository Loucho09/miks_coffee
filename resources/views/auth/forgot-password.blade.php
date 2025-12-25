<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-stone-100  dark:bg-stone-950 px-4">
        
        <div class="w-full max-w-md bg-white dark:bg-stone-900 rounded-3xl shadow-2xl overflow-hidden border border-stone-100 dark:border-stone-800">
            <div class="bg-amber-600 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/coffee-beans.png')]"></div>
                <div class="bg-white/20 backdrop-blur-md w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 relative z-10 shadow-inner">
                    <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-12 h-12 object-cover">
                </div>
                <h2 class="text-2xl font-bold text-white relative z-10">Password Reset</h2>
                <p class="text-amber-100 text-sm mt-1 relative z-10 font-medium">We'll help you get back to your brew.</p>
            </div>

            <div class="p-8">
                <p class="text-stone-500 dark:text-stone-400 mb-8 text-sm text-center leading-relaxed">
                    Forgot your password? No problem. Enter your email address and we will send you a reset link.
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-bold text-stone-700 dark:text-stone-300 mb-2">Email Address</label>
                        <input id="email" class="block w-full px-4 py-3.5 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-100  dark:bg-stone-800 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition outline-none" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-4">
                        <button type="submit" class="w-full bg-stone-900 dark:bg-white dark:text-stone-900 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            Send Reset Link
                        </button>

                        <a href="{{ route('login') }}" class="w-full text-center text-stone-500 hover:text-amber-600 dark:text-stone-400 dark:hover:text-amber-500 text-sm font-bold py-2 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>