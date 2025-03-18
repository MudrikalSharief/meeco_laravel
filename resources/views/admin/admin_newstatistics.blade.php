
<x-admin_layout>
    <main class="ml-5 p-4">
    <div class="flex justify-center container bg-gray-100">
        <!-- Daily Income Chart -->
        <div class="w-full mx-auto bg-white p-5 rounded-lg shadow-md mb-6">
            <h1 class="text-center text-2xl font-bold text-gray-800 mb-6">Subscription Revenue</h1>
           
            <!-- Date range selector - Make "To" date automatically adjust -->
            <div class="flex flex-col justify-center items-center mb-6 space-y-4">

                <div id = "filterWeeks" class="flex  space-x-4 items-center">
                    <div>
                        <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="date" id="dateFrom" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="date" id="dateTo" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div id = "filterMonths" class="flex space-x-4 items-center" style="display:none">
                    <div>
                        <label for="monthFrom" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="month" id="monthFrom" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="monthTo" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="month" id="monthTo" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
<!-- 
                <div id = "filterYears" class="flex items-center space-x-2" style="display:none">
                    <label for="yearSelect" class="text-sm font-medium text-gray-700">Year</label>
                    <select id="yearSelect" class="border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                </div> -->

            </div>
            
            <!-- Summary stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                    <p id="totalRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <h3 class="text-sm font-medium text-gray-500">Total Subscriptions</h3>
                    <p id="totalSubs" class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                    <h3 class="text-sm font-medium text-gray-500">Avg. Revenue Per User</h3>
                    <p id="avgRevenue" class="text-2xl font-bold text-gray-800">PHP 0</p>
                </div>
            </div>

            <div class="relative h-96 mt-5">
                <div class="inline-flex border border-gray-300 rounded-md overflow-hidden">
                    <button id="btn-weekly" class="px-4 py-2 text-sm font-medium bg-blue-500 text-white 
                        focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Weekly Revenue
                    </button>
                    <button id="btn-yearly" class="px-4 py-2 text-sm font-medium bg-gray-200 text-gray-700 
                        hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Yearly Revenue
                    </button>
                </div>
            
            <div id="weeklyChart">
                <div id="weekButtons" class="flex flex-wrap gap-2 justify-center mt-4"></div>
                <div class="flex">
                    <canvas id="weeklyRevenue" class="w-full h-full"></canvas>
                </div>
            </div>

            <div id="yearlyChart" style = "display:none;">
                <div id="yearButtons" class="flex flex-wrap gap-2 justify-center mt-4"></div>
                <div class="relative h-96 mt-5">
                    <canvas id="yearlyRevenue" class="w-full h-full"></canvas>
                </div>
            </div>
            </div>
        </div>

        </div>
    </main>

</x-admin_layout>