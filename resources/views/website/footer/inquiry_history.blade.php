<x-web_footer>
    <main class="p-10">
        <div class="flex items-center gap-3 mb-10">
            <a href="{{ route('contact') }}" class="text-blue-600 text-3xl no-underline">←</a>
            <h1 class="text-blue-600 text-2xl font-bold">Inquiry History</h1>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-left p-3 text-gray-600 font-medium border-b border-gray-200">Subject</th>
                    <th class="text-left p-3 text-gray-600 font-medium border-b border-gray-200">Ticket Reference No</th>
                    <th class="text-left p-3 text-gray-600 font-medium border-b border-gray-200">Category</th>
                    <th class="text-left p-3 text-gray-600 font-medium border-b border-gray-200">Last Post</th>
                    <th class="text-left p-3 text-gray-600 font-medium border-b border-gray-200">Status</th>
                    <th class="border-b border-gray-200"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr>
                    <td class="p-4 text-gray-800 border-b border-gray-200">{{ $inquiry->subject }}</td>
                    <td class="p-4 text-gray-600 border-b border-gray-200">{{ $inquiry->ticket_reference }}</td>
                    <td class="p-4 text-gray-600 border-b border-gray-200">{{ $inquiry->category }}</td>
                    <td class="p-4 text-gray-600 border-b border-gray-200">{{ $inquiry->updated_at }}</td>
                    <td class="p-4 text-gray-600 border-b border-gray-200">{{ $inquiry->status }}</td>
                    <td class="p-4 border-b border-gray-200">
                        <a href="{{ route('inquiry.details', ['ticket_reference' => $inquiry->ticket_reference]) }}" class="bg-blue-400 text-white border-none py-2 px-6 rounded cursor-pointer text-sm hover:bg-blue-600">Reply</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</x-web_footer>