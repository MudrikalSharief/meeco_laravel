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

        <input type="text" placeholder="Search" id="search-transactions">

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

        <!-- Pagination with Arrow Navigation -->
        <div class="pagination">
            <!-- Left Arrow -->
            @if ($transactions->onFirstPage())
                <span class="arrow disabled">&laquo; Previous</span>
            @else
                <a href="{{ $transactions->previousPageUrl() }}" class="arrow">&laquo; Previous</a>
            @endif

            <!-- Pagination Links -->
            @foreach(range(1, $transactions->lastPage()) as $page)
                @if ($page == $transactions->currentPage())
                    <span class="current-page">{{ $page }}</span>
                @else
                    <a href="{{ $transactions->url($page) }}">{{ $page }}</a>
                @endif
            @endforeach

            <!-- Right Arrow -->
            @if ($transactions->hasMorePages())
                <a href="{{ $transactions->nextPageUrl() }}" class="arrow">Next &raquo;</a>
            @else
                <span class="arrow disabled">Next &raquo;</span>
            @endif
        </div>
    </main>
</x-admin_layout>