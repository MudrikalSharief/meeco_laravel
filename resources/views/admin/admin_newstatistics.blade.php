<x-admin_layout>
    <main class="ml-5 p-4">
        <!-- Daily Income Chart -->
        <div class="w-full mx-auto bg-white p-5 rounded-lg shadow-md mb-6">
            <h1 class="text-center text-2xl font-bold text-gray-800 mb-6">Daily Subscription Income</h1>
            
            <!-- Date range selector - Make "To" date automatically adjust -->
            <div class="flex justify-center mb-6">
                <div class="flex space-x-4 items-center">
                    <div>
                        <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="date" id="dateFrom" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">To (7 days from start)</label>
                        <input type="date" id="dateTo" class="border border-gray-300 bg-gray-100 rounded-md px-3 py-1.5 cursor-not-allowed" readonly disabled>
                    </div>
                    <div class="pt-6">
                        <button id="applyDateFilter" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">Apply</button>
                    </div>
                </div>
            </div>
            
            <!-- Summary stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                    <p id="totalRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <h3 class="text-sm font-medium text-gray-500">Total Subscriptions</h3>
                    <p id="totalSubscriptions" class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                    <h3 class="text-sm font-medium text-gray-500">Avg. Revenue Per User</h3>
                    <p id="avgRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
            </div>
            
            <div class="relative h-96 mt-5">
                <canvas id="incomeChart" class="w-full h-full"></canvas>
            </div>
            
            <div class="flex justify-center items-center mt-5">
                <div class="flex items-center mx-2">
                    <div class="w-5 h-2.5 bg-[#39a9ff] mr-1.5"></div>
                    <div>Daily Income (PHP)</div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Income Chart -->
        <div class="w-full mx-auto bg-white p-5 rounded-lg shadow-md">
            <h1 class="text-center text-2xl font-bold text-gray-800 mb-6">Monthly Subscription Income</h1>
            
            <!-- Year selector -->
            <div class="flex justify-center mb-6">
                <div class="flex space-x-4 items-center">
                    <div>
                        <label for="yearSelect" class="block text-sm font-medium text-gray-700 mb-1">Select Year</label>
                        <select id="yearSelect" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <!-- We'll populate this dynamically -->
                        </select>
                    </div>
                    <div class="pt-6">
                        <button id="applyYearFilter" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded">Apply</button>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Summary stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h3 class="text-sm font-medium text-gray-500">Annual Revenue</h3>
                    <p id="annualRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <h3 class="text-sm font-medium text-gray-500">Annual Subscriptions</h3>
                    <p id="annualSubscriptions" class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                    <h3 class="text-sm font-medium text-gray-500">Avg. Monthly Revenue</h3>
                    <p id="avgMonthlyRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
            </div>
            
            <div class="relative h-96 mt-5">
                <canvas id="monthlyIncomeChart" class="w-full h-full"></canvas>
            </div>
            
            <div class="flex justify-center items-center mt-5">
                <div class="flex items-center mx-2">
                    <div class="w-5 h-2.5 bg-[#4CAF50] mr-1.5"></div>
                    <div>Monthly Income (PHP)</div>
                </div>
            </div>
        </div>
    </main>

    <script>
        
    </script>
</x-admin_layout>