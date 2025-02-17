<x-admin_layout>
    <main>
        <select name="" id="">
            <option value=""></option>
        </select>
        <select name="" id="">
            <option value=""></option>
        </select>
        <input type="text" placeholder="Search">

        <table>
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
                        <td>{{ $transaction->user->name }}</td>
                        <td>{{ $transaction->promo->name }}</td>
                        <td>{{ $transaction->reference_no }}</td>
                        <td>{{ $transaction->start_date }}</td>
                        <td>{{ $transaction->end_date }}</td>
                        <td>{{ $transaction->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</x-admin_layout>