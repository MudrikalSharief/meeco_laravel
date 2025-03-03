<x-web_footer>
    <main class="p-10">
        <div class="max-w-2xl mx-auto">
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
        </div>
    </main>
</x-web_footer>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-md p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Reply to Inquiry</h2>
        <form id="replyForm" action="{{ route('submitReply', ['ticket_reference' => $inquiry->ticket_reference]) }}" method="POST" enctype="multipart/form-data" onsubmit="submitReply(event)">
            @csrf
            <div class="mb-4">
                <label for="reply_user_question" class="block text-sm font-medium text-gray-700">Reply</label>
                <textarea id="reply_user_question" name="reply_user_question" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="reply_user_upload" class="block text-sm font-medium text-gray-700">Upload</label>
                <input type="file" id="reply_user_upload" name="reply_user_upload[]" multiple accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" class="bg-transparent border border-gray-300 text-gray-600 py-2 px-4 rounded-md hover:bg-gray-100" onclick="toggleModal()">Close</button>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-500">Reply</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        const modal = document.getElementById('replyModal');
        modal.classList.toggle('hidden');
    }

    async function submitReply(event) {
        event.preventDefault();
        const replyForm = document.getElementById('replyForm');
        const formData = new FormData(replyForm);
        const replyUserQuestion = formData.get('reply_user_question');
        const replyUserUpload = formData.getAll('reply_user_upload[]');
        const currentTime = new Date().toLocaleString();

        // Add reply to the database
        const response = await fetch(replyForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.ok) {
            const replySection = document.createElement('div');
            replySection.className = 'custom-border rounded-md p-6 mb-5';

            const replyContent = document.createElement('div');
            replyContent.className = 'flex items-start gap-3';

            const replyText = document.createElement('div');
            replyText.className = 'flex-1';
            replyText.innerHTML = `<p class="text-sm leading-6 text-gray-600 mb-2">${replyUserQuestion}</p>
                                   <div class="text-xs text-gray-500 mb-2">${currentTime}</div>`;

            if (replyUserUpload.length > 0) {
                const uploadList = document.createElement('div');
                uploadList.className = 'text-sm text-blue-600';
                replyUserUpload.forEach(upload => {
                    const uploadLink = document.createElement('a');
                    uploadLink.href = URL.createObjectURL(upload);
                    uploadLink.className = 'hover:opacity-90';
                    uploadLink.textContent = upload.name;
                    uploadList.appendChild(uploadLink);
                });
                replyText.appendChild(uploadList);
            }

            replyContent.appendChild(replyText);
            replySection.appendChild(replyContent);

            const mainContent = document.querySelector('main .max-w-2xl');
            mainContent.insertBefore(replySection, mainContent.lastElementChild);

            toggleModal();
        } else {
            console.error('Failed to submit reply');
        }
    }
</script>

<style>
    .custom-border {
        border-left: 4px solid #1D4ED8; /* Blue color */
        border-top: 1px solid #D9D9D9; /* Light gray color */
        border-right: 1px solid #D9D9D9; /* Light gray color */
        border-bottom: 1px solid #D9D9D9; /* Light gray color */
    }
</style>
