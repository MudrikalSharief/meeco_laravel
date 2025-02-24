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
    </main>
</x-admin_layout>