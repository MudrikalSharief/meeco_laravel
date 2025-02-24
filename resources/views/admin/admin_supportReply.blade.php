<x-admin_layout>
  <main class="p-5">
    <div class="flex items-center gap-3 mb-10">
        <a href="{{ route('inquiry-history') }}" class="text-blue-600 text-3xl no-underline">←</a>
        <h1 class="text-blue-600 text-2xl font-medium">Inquiry Details</h1>
    </div>
    <div class="custom-border rounded-md p-6 mb-5">
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

    @if($inquiry->replies)
        @foreach($inquiry->replies as $reply)
            <div class="custom-border rounded-md p-6 mb-5">
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="text-sm leading-6 text-gray-600 mb-2">{{ $reply->reply_user_question }}</p>
                        <div class="text-xs text-gray-500 mb-2">{{ $reply->created_at }}</div>
                        @if($reply->reply_user_upload)
                            @foreach(json_decode($reply->reply_user_upload) as $upload)
                                <a href="{{ asset('storage/' . $upload) }}" class="text-blue-600 text-sm hover:opacity-90">{{ $upload }}</a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="flex justify-end gap-3">
        <button type="button" class="bg-blue-600 text-white py-2 px-3 rounded-md hover:bg-blue-500" onclick="toggleModal()">Reply</button>
        <form action="{{ route('inquiry-history') }}" method="GET">
            <button type="submit" class="bg-transparent border border-gray-300 text-gray-600 py-2 px-4 rounded-md hover:bg-gray-100">Close Question</button>
        </form>
    </div>
  </main>
</x-admin_layout>
<style>
    .custom-border {
        border-left: 4px solid #1D4ED8; /* Blue color */
        border-top: 1px solid #D9D9D9; /* Light gray color */
        border-right: 1px solid #D9D9D9; /* Light gray color */
        border-bottom: 1px solid #D9D9D9; /* Light gray color */
    }
</style>
