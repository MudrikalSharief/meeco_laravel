<x-admin_layout>
    <main>
        <div class="flex justify-center container bg-gray-100">
            <div class="daily-rev" data-daily-rev='{!! json_encode($daily_rev) !!}'></div>
            <div class="monthly-rev" data-monthly-rev='{!! json_encode($monthly_rev) !!}'></div>
            <select id="select-rev">
                <option value="opt-daily">Daily Revenue</option>
                <option value="opt-monthly">Monthly Revenue</option>
            </select>
            <canvas id="dailyChart"></canvas>
            <canvas id="monthlyChart" style = "display:none;"></canvas>
            <div>Sales</div>
        </div>

        <div class="flex justify-center container bg-gray-100">
            <div class="daily-ol" data-daily-rev='{!! json_encode($daily_ol) !!}'></div>
            <div class="monthly-ol" data-monthly-rev='{!! json_encode($monthly_ol) !!}'></div>
            <select id="select-ol">
                <option value="opt-daily-ol">Daily Online Users</option>
                <option value="opt-monthly-ol">Monthly Online Users</option>
            </select>
            <canvas id="dailyOlChart"></canvas>
            <canvas id="monthlyOlChart" style = "display:none;"></canvas>
            <div>Sales</div>
        </div>
    </main>
</x-admin_layout>