<x-admin_layout>
    <main class="ml-5 p-4">
        <!-- Daily Income Chart -->
        <div class="w-full bg-white p-5 rounded-lg shadow-md">
            <h1 class="text-center text-2xl font-bold text-gray-800 mb-3">Subscription Revenue</h1>
           
            <!-- Date range selector - Make "To" date automatically adjust -->
            <div class="flex flex-col justify-center items-center mb-3 space-y-4">

                <div id = "filterWeeks" class="flex flex-col items-center mt-4">
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
                    <div class="pt-6">
                        <button id="printDailyPdf" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-4 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Print PDF
                        </button>
                    </div>
                    <div class="pt-6">
                        <button id="printDailyExcel" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-4 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Print Excel
                        </button>
                    </div>
                </div>
                <div id = "filterMonths" class="flex flex-col items-center" style="display:none">
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
                    <p id="totalSubscriptions" class="text-2xl font-bold text-gray-800">0</p>
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
            <div id="weekButtons" class="flex flex-wrap gap-2 justify-center"></div>
                <div class="flex">
                 <canvas id="weeklyRevenue" class="w-full h-full mt-4"></canvas> 

                </div>
            </div>

            <div id="yearlyChart" style = "display:none;">
                <div id="yearButtons" class="flex flex-wrap gap-2 justify-center"></div>
                <div class="relative h-96 mt-5">
                    <canvas id="yearlyRevenue" class="w-full h-full mt-4"></canvas>
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
                    <div class="pt-6">
                        <button id="printMonthlyPdf" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-4 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Print PDF
                        </button>
                    </div>
                    <div class="pt-6">
                        <button id="printMonthlyExcel" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-4 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Print Excel
                        </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Get the PDF buttons
            const printDailyPdfBtn = document.getElementById('printDailyPdf');
            const printMonthlyPdfBtn = document.getElementById('printMonthlyPdf');
            
            // Get the Excel buttons
            const printDailyExcelBtn = document.getElementById('printDailyExcel');
            const printMonthlyExcelBtn = document.getElementById('printMonthlyExcel');
            
            // Add click handlers for the PDF buttons
            if (printDailyPdfBtn) {
                printDailyPdfBtn.addEventListener('click', function() {
                    const fromDate = document.getElementById('dateFrom').value;
                    if (!fromDate) {
                        alert('Please select a From date first.');
                        return;
                    }
                    
                    // Calculate toDate (7 days from fromDate)
                    const toDateObj = new Date(fromDate);
                    toDateObj.setDate(toDateObj.getDate() + 6);
                    const toDate = toDateObj.toISOString().split('T')[0];
                    
                    // Generate the PDF URL with query parameters
                    const url = `{{ route('admin.statistics.daily-pdf') }}?from_date=${fromDate}&to_date=${toDate}`;
                    window.open(url, '_blank');
                });
            }
            
            if (printMonthlyPdfBtn) {
                printMonthlyPdfBtn.addEventListener('click', function() {
                    const year = document.getElementById('yearSelect').value;
                    if (!year) {
                        alert('Please select a year first.');
                        return;
                    }
                    
                    // Generate the PDF URL with query parameters
                    const url = `{{ route('admin.statistics.monthly-pdf') }}?year=${year}`;
                    window.open(url, '_blank');
                });
            }
            
            // Add click handlers for the Excel buttons
            if (printDailyExcelBtn) {
                printDailyExcelBtn.addEventListener('click', function() {
                    const fromDate = document.getElementById('dateFrom').value;
                    if (!fromDate) {
                        alert('Please select a From date first.');
                        return;
                    }
                    
                    // Calculate toDate (7 days from fromDate)
                    const toDateObj = new Date(fromDate);
                    toDateObj.setDate(toDateObj.getDate() + 6);
                    const toDate = toDateObj.toISOString().split('T')[0];
                    
                    // Generate the Excel URL with query parameters
                    const url = `{{ route('admin.statistics.daily-excel') }}?from_date=${fromDate}&to_date=${toDate}`;
                    window.open(url, '_blank');
                });
            }
            
            if (printMonthlyExcelBtn) {
                printMonthlyExcelBtn.addEventListener('click', function() {
                    const year = document.getElementById('yearSelect').value;
                    if (!year) {
                        alert('Please select a year first.');
                        return;
                    }
                    
                    // Generate the Excel URL with query parameters
                    const url = `{{ route('admin.statistics.monthly-excel') }}?year=${year}`;
                    window.open(url, '_blank');
                });
            }

            // Function to update the daily statistics UI
            function updateDailyStats(data) {
                // Update summary statistics
                document.getElementById('totalRevenue').innerText = 'PHP ' + data.summary.totalRevenue.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.getElementById('totalSubscriptions').innerText = data.summary.totalSubscriptions;
                document.getElementById('avgRevenue').innerText = 'PHP ' + data.summary.avgRevenue.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // ... rest of your chart update code ...
            }
            
            // Get the date filter button
            const applyDateFilterBtn = document.getElementById('applyDateFilter');
            
            // Add event listener for the date filter button
            if (applyDateFilterBtn) {
                applyDateFilterBtn.addEventListener('click', function() {
                    const fromDate = document.getElementById('dateFrom').value;
                    if (!fromDate) {
                        alert('Please select a From date first.');
                        return;
                    }
                    
                    // Calculate toDate (7 days from fromDate)
                    const toDateObj = new Date(fromDate);
                    toDateObj.setDate(toDateObj.getDate() + 6);
                    const toDate = toDateObj.toISOString().split('T')[0];
                    
                    // Update the toDate field for display
                    document.getElementById('dateTo').value = toDate;
                    
                    // Fetch the subscription statistics
                    fetch(`{{ route('admin.subscription-stats') }}?from_date=${fromDate}&to_date=${toDate}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error('Error:', data.error);
                                alert('Error fetching statistics: ' + data.error);
                                return;
                            }
                            
                            // Update the daily statistics
                            updateDailyStats(data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error fetching statistics: ' + error);
                        });
                });
            }
        });
    </script>
</x-admin_layout>