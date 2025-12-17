<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50 px-4">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-8 text-center">
                <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <span class="text-3xl">🔒</span>
                </div>
                <h2 class="text-2xl font-bold text-white">Reset Password</h2>
                <p class="text-amber-100 text-sm mt-2">We'll help you get back in.</p>
            </div>

            <div class="p-8">
                <p class="text-gray-600 mb-6 text-sm leading-relaxed text-center">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-amber-500 transition shadow-sm outline-none" 
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Enter your email address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-4">
                        <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                            {{ __('Email Password Reset Link') }}
                        </button>

                        <a href="{{ route('login') }}" class="w-full text-center text-gray-500 hover:text-gray-800 text-sm font-medium py-2 transition flex items-center justify-center gap-2">
                            <span>&larr;</span> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</x-guest-layout>