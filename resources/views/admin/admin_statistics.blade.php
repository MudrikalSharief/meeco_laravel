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
            <div>Sales</div>
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
                <div id="ol-count"></div>
            </div>
        </div>
    </main>
</x-admin_layout>