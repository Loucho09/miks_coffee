<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-stone-50 dark:bg-stone-900 px-4">
        
        <div class="w-full max-w-md bg-white dark:bg-stone-800 rounded-2xl shadow-xl overflow-hidden border border-stone-100 dark:border-stone-700">
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-white opacity-10" style="background-image: url('data:image/svg+xml,...');"></div>
                
                <div class="bg-white/20 backdrop-blur-sm w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white relative z-10">Reset Password</h2>
                <p class="text-amber-100 text-sm mt-1 relative z-10">We'll help you get back in.</p>
            </div>

            <div class="p-8">
                <p class="text-stone-600 dark:text-stone-300 mb-6 text-sm text-center leading-relaxed">
                    Forgot your password? No problem. Enter your email address and we will email you a password reset link.
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-bold text-stone-700 dark:text-stone-300 mb-1.5">Email Address</label>
                        <input id="email" class="block w-full px-4 py-3 rounded-xl border border-stone-300 dark:border-stone-600 bg-stone-50 dark:bg-stone-700 text-stone-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition outline-none shadow-sm" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-4">
                        <button type="submit" class="w-full bg-stone-900 dark:bg-white dark:text-stone-900 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                            Email Password Reset Link
                        </button>

                        <a href="{{ route('login') }}" class="w-full text-center text-stone-500 hover:text-stone-800 dark:text-stone-400 dark:hover:text-stone-200 text-sm font-bold py-2 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>