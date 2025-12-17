<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('Customer Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-300">
                
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
                    Total Registered Customers: {{ $customers->count() }}
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600 text-gray-700 dark:text-gray-200">
                                <th class="p-3">ID</th>
                                <th class="p-3">Name</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">Joined Date</th>
                                <th class="p-3 text-center">Total Orders</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customers as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    
                                    <td class="p-3 text-gray-500 dark:text-gray-400">#{{ $user->id }}</td>
                                    
                                    <td class="p-3 font-bold text-gray-800 dark:text-white">{{ $user->name }}</td>
                                    
                                    <td class="p-3 text-blue-600 dark:text-blue-400">{{ $user->email }}</td>
                                    
                                    <td class="p-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                                    
                                    <td class="p-3 text-center">
                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ $user->orders_count }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-3 text-right">
                                        <a href="{{ route('admin.customers.show', $user->id) }}" class="bg-gray-800 dark:bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 dark:hover:bg-gray-500 transition">
                                            View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>