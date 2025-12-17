<x-guest-layout>
    <div class="flex min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
        
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2070&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-white px-12 text-center">
                    <h1 class="text-5xl font-bold mb-6 tracking-tight">Join the Club.</h1>
                    <p class="text-xl font-light opacity-90">Earn points, get rewards, and order your favorites faster.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-center w-full lg:w-1/2 px-8 lg:px-24 py-12">
            
            <div class="w-full max-w-md mx-auto">
                <div class="mb-10 text-center lg:text-left">
                    <div class="flex justify-center lg:justify-start mb-4">
                        <span class="text-5xl">☕</span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Create Account</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Start your Miks Coffee journey today.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition outline-none shadow-sm"
                            placeholder="John Doe">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" :value="old('email')" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition outline-none shadow-sm"
                            placeholder="you@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition outline-none shadow-sm"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-8">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 transition outline-none shadow-sm"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-xl transition duration-200 transform hover:-translate-y-0.5">
                        {{ __('Sign Up') }}
                    </button>

                    <div class="mt-8 text-center border-t border-gray-200 dark:border-gray-700 pt-6">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-800 dark:text-amber-500 dark:hover:text-amber-400 font-bold hover:underline ml-1">
                                Log in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>