<x-admin_layout>
  <main class="p-5">
    <div class="min-w-full mx-auto bg-white rounded-lg p-5 shadow-sm border border-gray-150">
      <form method="GET" action="{{ route('filter.inquiries') }}">
        <div class="flex flex-wrap gap-4 mb-5 items-center">
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-800">Status</span>
            <select class="p-2 border border-gray-200 rounded-md bg-white text-sm appearance-none bg-no-repeat bg-right-2 bg-center bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e')] min-w-[100px]" name="status" onchange="this.form.submit()">
              <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
              <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
              <option value="Responded" {{ request('status') == 'Responded' ? 'selected' : '' }}>Responded</option>
              <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
          </div>
          <div class="ml-auto flex gap-2 flex-1 max-w-md">
            <input type="text" class="flex-1 p-2 border border-gray-200 rounded-md text-sm" placeholder="Search">
            <button class="p-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Search</button>
          </div>
        </div>
      </form>

      <table class="w-full border-collapse mb-5">
        <thead>
          <tr>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Ticket No.</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Reference Id.</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Email</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Create Date</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Last Post</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Status</th>
        <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($InquiriesAdmin->isEmpty())
        <tr>
          <td colspan="7" class="text-center text-gray-600 py-5">No data available</td>
        </tr>
          @else
        @foreach($InquiriesAdmin as $inquiry)
        <tr>
          <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $inquiry->ticket_id}}</td>
          <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $inquiry->ticket_reference}}</td>
          <td class="p-3 border-b border-gray-200 text-sm">{{ $inquiry->email }}</td>
          <td class="p-3 border-b border-gray-200 text-sm">{{ $inquiry->created_at }}</td>
          <td class="p-3 border-b border-gray-200 text-sm">{{ $inquiry->updated_at }}</td>
          <td class="p-3 border-b border-gray-200 text-sm font-medium 
          @if($inquiry->status == 'Pending') status-pending 
          @elseif($inquiry->status == 'Responded') status-responded 
          @elseif($inquiry->status == 'Closed') status-closed 
          @endif">
          {{ $inquiry->status }}
          </td>
          <td class="p-3 border-b border-gray-200 text-sm">
          <a href="{{ route('inquiry.details', $inquiry->ticket_reference) }}" class="bg-blue-100 border-none p-2 rounded-md cursor-pointer text-blue-600 hover:bg-blue-200 inline-block w-2/2 text-center">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
            </a>
          </td>
        </tr>
        @endforeach
          @endif
        </tbody>
      </table>

      <div class="flex justify-end items-center gap-3">
        <button class="p-2 border border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-100 w-10">←</button>
        <span class="text-sm text-gray-600 font-medium">1 / 1</span>
        <button class="p-2 border border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-100 w-10">→</button>
      </div>
    </div>
  </main>
</x-admin_layout>
