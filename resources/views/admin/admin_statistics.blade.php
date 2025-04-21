<x-admin_layout>
    <main>
        <div class="flex justify-center container bg-gray-100">
            <select id="select-rev">
                <option value="opt-daily">Daily Revenue</option>
                <option value="opt-monthly">Monthly Revenue</option>
                <option value="opt-yearly">Yearly Revenue</option>
            </select>
            <canvas id="dailyChart"></canvas>
            <canvas id="monthlyChart" style = "display:none;"></canvas>
            <canvas id="yearlyChart" style = "display:none;"></canvas>
            <div>
                <p>Sales</p>
                <div id="daily-rev-count"></div>
                <div id="monthly-rev-count" style = "display:none;"></div>
                <div id="yearly-rev-count" style = "display:none;"></div>
            </div>
        </div>
        <div class="flex justify-center container bg-gray-100">
            <select id="select-ol">
                <option value="opt-daily-ol">Daily Online Users</option>
                <option value="opt-monthly-ol">Monthly Online Users</option>
                <option value="opt-yearly-ol">Yearly Online Users</option>
            </select>
            <canvas id="dailyOlChart"></canvas>
            <canvas id="monthlyOlChart" style = "display:none;"></canvas>
            <canvas id="yearlyOlChart" style = "display:none;"></canvas>
            <div>
                <p>Users</p>
                
                <div id="daily-ol-count"></div>
                <div id="monthly-ol-count" style = "display:none;"></div>
                <div id="yearly-ol-count" style = "display:none;"></div>
            </div>
        </div>
    </main>
</x-admin_layout>