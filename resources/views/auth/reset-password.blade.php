<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-900 px-4 transition-colors duration-300">
        
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-8 text-center">
                <div class="bg-white dark:bg-gray-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                    <span class="text-3xl">🔑</span>
                </div>
                <h2 class="text-2xl font-bold text-white">Create New Password</h2>
                <p class="text-amber-100 text-sm mt-2">Make it strong and secure.</p>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input id="email" class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 outline-none" 
                            type="email" name="email" :value="old('email', $request->email)" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                        <div class="relative">
                            <input id="password" class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 outline-none" 
                                type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            <button type="button" onclick="togglePass('password', 'eye1')" class="absolute inset-y-0 right-0 px-4 text-gray-500 dark:text-gray-400">
                                <span id="eye1">👁️</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                        <div class="relative">
                            <input id="password_confirmation" class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-amber-500 outline-none" 
                                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                            <button type="button" onclick="togglePass('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 px-4 text-gray-500 dark:text-gray-400">
                                <span id="eye2">👁️</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-gray-900 dark:bg-amber-600 hover:bg-gray-800 dark:hover:bg-amber-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                        {{ __('Reset Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePass(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "🙈";
            } else {
                input.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</x-guest-layout>