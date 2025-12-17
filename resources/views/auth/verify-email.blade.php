<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-stone-50 dark:bg-stone-900 px-4">
        
        <div class="w-full max-w-md bg-white dark:bg-stone-800 rounded-2xl shadow-xl border border-stone-100 dark:border-stone-700 overflow-hidden">
            
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-stone-900 dark:text-white mb-4">Check Your Email</h2>
                
                <p class="text-stone-600 dark:text-stone-300 mb-6 text-sm leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm font-medium p-4 rounded-lg border border-green-200 dark:border-green-800">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-stone-900 dark:bg-white dark:text-stone-900 text-white font-bold py-3 rounded-xl shadow-md hover:opacity-90 transition">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-stone-500 hover:text-stone-800 dark:hover:text-stone-300 font-bold underline transition">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>