<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                 Customer: {{ $customer->name }}
            </h2>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                &larr; Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="space-y-6">
                
                <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg transition-colors duration-300">
                    <h3 class="font-bold text-lg mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-white">
                        Account Details
                    </h3>
                    
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Name</p>
                    <p class="font-medium mb-3 text-gray-900 dark:text-gray-200">{{ $customer->name }}</p>
                    
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Email</p>
                    <p class="font-medium mb-3 text-gray-900 dark:text-gray-200">{{ $customer->email }}</p>

                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Member Since</p>
                    <p class="font-medium text-gray-900 dark:text-gray-200">{{ $customer->created_at->format('F j, Y') }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg border-l-4 border-red-500 transition-colors duration-300">
                    <h3 class="font-bold text-lg mb-2 text-red-600 dark:text-red-400">Admin Action: Reset Password</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Set a new temporary password for this user.
                    </p>

                    @if(session('success'))
                        <div class="mb-4 text-green-600 dark:text-green-400 text-sm font-bold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.customers.reset_password', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                            <input type="password" name="new_password" required 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition-colors">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" required 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition-colors">
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-bold shadow transition">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg transition-colors duration-300">
                <h3 class="font-bold text-lg mb-4 border-b dark:border-gray-700 pb-2 text-gray-800 dark:text-white">
                    Order History
                </h3>

                @if($customer->orders->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No orders found for this customer.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="p-3">Order ID</th>
                                    <th class="p-3">Date</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Payment</th>
                                    <th class="p-3">Items Ordered</th>
                                    <th class="p-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($customer->orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="p-3 font-medium text-gray-900 dark:text-white align-top">#{{ $order->id }}</td>
                                        <td class="p-3 text-gray-600 dark:text-gray-300 align-top whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="p-3 align-top">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                                {{ $order->status == 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : '' }}
                                                {{ $order->status == 'pending' ? 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-200' : '' }}
                                                {{ $order->status == 'cancelled' ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-gray-600 dark:text-gray-300 align-top">
                                            {{ ucfirst($order->payment_method ?? 'Cash') }}
                                        </td>
                                        
                                        <td class="p-3 text-gray-600 dark:text-gray-300 align-top text-xs">
                                            <ul class="list-disc list-inside">
                                                @foreach($order->items as $item)
                                                    <li>
                                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $item->quantity }}x</span> 
                                                        {{ $item->product->name ?? 'Deleted Item' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td class="p-3 font-bold text-right text-gray-900 dark:text-white align-top">
                                            ₱{{ number_format($order->total_price ?? $order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>