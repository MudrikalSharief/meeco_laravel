<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<x-admin_layout>
    <main>
        <select name="" id="subscription-filter">
            <option value="All">All</option>
            <option value="Subscribed">Subscribed</option>
            <option value="Admin Granted">Admin Granted</option>
        </select>
        <select name="" id="sort-by">
            <option value="Sort By">Sort By</option>
            <option value="Users">Users</option>
            <option value="Promo Type">Promo Type</option>
            <option value="Date">Date</option>
            <option value="Amount">Amount</option>
        </select>

        <table id = "subsTable">
            <thead>
                <tr>
                    <th class="text-center">User</th>
                    <th class="text-center">Promo Type</th>
                    <th class="text-center">Reference Number</th>
                    <th class="text-center">Start Date</th>
                    <th class="text-center">End Date</th>
                    <th class="text-center">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->user->firstname }} {{ $transaction->user->lastname }}</td>
                        <td>{{ $transaction->promo->name }}</td>
                        <td>{{ $transaction->reference_no }}</td>
                        <td>{{ $transaction->start_date }}</td>
                        <td>{{ $transaction->end_date }}</td>
                        <td>{{ $transaction->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    </main>
</x-admin_layout>