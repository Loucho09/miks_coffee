<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600">
                    💰
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                    📦
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-amber-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Actions</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingOrders }}</p>
                </div>
                <div class="p-3 bg-amber-100 rounded-full text-amber-600">
                    ⚠️
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCustomers }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                    👥
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Weekly Sales Performance</h3>
            <div class="relative" style="height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-bold text-gray