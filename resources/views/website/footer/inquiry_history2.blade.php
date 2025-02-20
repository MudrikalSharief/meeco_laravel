<x-web_footer>
    <main class="p-10">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-3 mb-10">
                <a href="{{ route('inquiry-history') }}" class="text-blue-600 text-2xl no-underline">←</a>
                <h1 class="text-blue-600 text-xl font-medium">Inquiry Details</h1>
            </div>
            <div class="border-2 border-blue-600 rounded-lg p-6 mb-5">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-4 h-4">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-semibold mb-2 text-gray-800">{{ $inquiry->subject }}</h2>
                        <p class="text-sm leading-6 text-gray-600 mb-2">{{ $inquiry->question }}</p>
                        <div class="text-xs text-gray-500 mb-2">{{ $inquiry->created_at }}</div>
                        @if($inquiry->upload)
                            @foreach(json_decode($inquiry->upload) as $upload)
                                <a href="{{ asset('storage/' . $upload) }}" class="text-blue-600 text-sm hover:opacity-90">{{ $upload }}</a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="flex justify-end gap-3">
                <form action="{{ route('submitReply', ['ticket_reference' => $inquiry->ticket_reference]) }}" method="POST">
                    @csrf
                   
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-500">Reply</button>
                </form>
                <form action="{{ route('closeInquiry', ['ticket_reference' => $inquiry->ticket_reference]) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-transparent border border-gray-300 text-gray-600 py-2 px-4 rounded-md hover:bg-gray-100">Close Question</button>
                </form>
            </div>
        </div>
    </main>
</x-web_footer>
