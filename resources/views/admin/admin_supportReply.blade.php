<x-admin_layout>
  <main class="p-5">
    <div class="flex items-center gap-3 mb-10">
        <a href="{{ route('admin.support') }}" class="text-blue-600 text-3xl no-underline">←</a>
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

    @if($inquiry->adminReplies)
        @foreach($inquiry->adminReplies as $reply)
            <div class="custom-border-admin rounded-md p-6 mb-5 ml-48">
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="text-sm leading-6 text-gray-600 mb-2">{{ $reply->reply_admin_question }}</p>
                        <div class="text-xs text-gray-500 mb-2">{{ $reply->created_at }}</div>
                        @if($reply->reply_admin_upload)
                            @foreach(json_decode($reply->reply_admin_upload) as $upload)
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

    <div id="replyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-md shadow-md w-full max-w-lg">
            <h2 class="text-xl font-semibold mb-4">Submit Reply</h2>
            <form action="{{ route('admin.submitReply', ['ticket_reference' => $inquiry->ticket_reference]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="reply_admin_question" class="block text-sm font-medium text-gray-700">Reply</label>
                    <textarea id="reply_admin_question" name="reply_admin_question" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="reply_admin_upload" class="block text-sm font-medium text-gray-700">Upload</label>
                    <input type="file" id="reply_admin_upload" name="reply_admin_upload[]" multiple class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400" onclick="toggleModal()">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>
  </main>
</x-admin_layout>

<script>
    function toggleModal() {
        const modal = document.getElementById('replyModal');
        modal.classList.toggle('hidden');
    }
</script>
