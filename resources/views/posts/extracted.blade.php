<?php
     require_once base_path('vendor/autoload.php');
     use Google\Cloud\Vision\V1\ImageAnnotatorClient;
     use App\Models\Raw;
     use App\Http\Controllers\RawController;

     $rawText = Raw::where('topic_id', request('topic_id'))->first()->raw_text ?? '';
?>
<x-layout>
    <div class="p-6 h-full  md:overflow-hidden">
        <h1 class="pb-3 text-xl font-bold text-blue-500">Extracted Text for Topic: {{ request('topic_name') }}</h1>
        <div class="flex flex-col md:flex-row h-full">
            <div class="image-container  md:max-h-[80vh] md:w-1/3 w-full">
                <h3 class=" mb-2 text-blue-400">Images Uploaded <hr></h3>
                <div class="image_holder flex gap-3 md:flex-col overflow-x-auto md:overflow-y-auto scrollable">
                    <?php
                    $image_paths = glob(storage_path('app/public/uploads/*.{jpg,jpeg,png,gif}'), GLOB_BRACE);
                    foreach ($image_paths as $index => $image_path) {
                        $publicImagePath = str_replace(storage_path('app/public'), 'storage', $image_path);
                        ?>
                        <div class="mb-4 mt-2 flex flex-col items-center min-w-32">
                            <img src="<?php echo asset($publicImagePath); ?>" alt="Image" class="w-32 h-32 object-cover cursor-pointer" onclick="openModal('<?php echo asset($publicImagePath); ?>')">
                            <p class="text-center">Image <?php echo $index + 1; ?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="text-container  w-full md:w-2/3 md:pl-4 pt-4 md:pt-0">
                <h3 class=" pb-2 text-blue-400">Extracted Text <hr></h3>
                <form id="extractedTextForm" class="w-full h-full" method="POST" action="/store-extracted-text">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ request('topic_id') }}">
                    <textarea name="raw_text" class="extractedTA w-full h-[calc(100vh-16rem)] md:h-3/4 p-2 border rounded"><?php echo htmlspecialchars($rawText); ?></textarea>
                    <button type="submit" class="generate_reviewer mb-5 md:mb-0 bg-green-500 text-white px-4 py-2 rounded mt-4 hover:bg-green-600">Generate Reviewer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" onclick="closeModal(event)">
        <div class="bg-white p-4 rounded" onclick="event.stopPropagation()" style="width: 80%; min-width: 270px;">
            <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full">
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded" style="width: 80%; min-width: 270px;">
            <p>Reviewer Generated!</p>
            <a href="{{route('reviewer')}}"><button class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600">Okay</button></a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const extractedTextForm = document.getElementById('extractedTextForm');
            const extractedTextArea = extractedTextForm.querySelector('textarea[name="raw_text"]');
            const topicId = extractedTextForm.querySelector('input[name="topic_id"]').value;

            fetch('/get-raw-text', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ topic_id: topicId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.raw_text.trim() !== '') {
                    extractedTextArea.value = data.raw_text;
                } else {
                    fetch('/extract-text', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ topic_id: topicId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            throw new Error('Response is not JSON');
                        }
                    })
                    .then(data => {
                        console.log('Success:', data);
                        // Display the returned raw_text in the textarea
                        extractedTextArea.value = data.raw_text;
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });

            extractedTextForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(extractedTextForm);
                fetch(extractedTextForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('successModal').classList.remove('hidden');
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            });
        });

        function closeModal(event) {
            if (event.target.id === 'imageModal') {
                document.getElementById('imageModal').classList.add('hidden');
            }
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }
    </script>
</x-layout>
