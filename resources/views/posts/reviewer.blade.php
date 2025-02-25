<x-layout>
    
    <div class="reviewer_whole_content max-w-2xl mx-auto pt-2 bg-white  rounded-lg">
   
        
        <!-- Content Header -->
        <div class="flex items-center justify-between mb-4 mx-5">
            <h1 class="TITLE text-2xl font-bold text-gray-800">Topic : {{ $topic->name }}</h1>
        </div>
    
        <!-- Buttons -->
        <div class="flex space-x-2 mx-5 ">
            <button class="px-2 py-1 bg-blue-100 font-semibold text-blue-700 text-sm rounded-sm hover:bg-blue-300">Reviewer</button>
            <button id="quiz" class=" px-2 py-1 bg-gray-100 text-sm text-gray-700  rounded-sm  hover:bg-gray-300">Quizzes</button>
        </div>
        {{-- rawtext-reviewer --}}
        <div class="mx-5 flex justify-between">
            <button id="toggleButton" class="mt-1 text-blue-300 text-sm font-medium rounded-lg hover:underline">View raw text</button>
            <button id="downloadReviewer" class="px-2 py-1 bg-green-100 text-sm text-green-700 rounded-sm hover:bg-green-300">
                <img class="max-w-3" src="{{ asset('logo_icons/download.svg') }}" alt="Logo">    
            </button>
        </div>

        <!-- Scrollable Content Box -->
        <div class="Reviewer mx-5 my-3 rounded-lg";>
            {{-- Reviewer  in here --}}
            <h1 class="reviewer_holder"></h1>
        </div>

        <div class="Rawtext hidden Reviewer mx-5 my-3 rounded-lg overflow-y-scroll";>
            {{-- Raw text in here --}}
            <h1 class="rawtext_holder">Raw Text: {{ $rawText }}</h1>
         </div>

     
    </div>

    <div id="extractTextModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-lg font-semibold mb-4">Extract Text</h2>
            <!-- Modal content here -->
            <div class="flex justify-end mt-4">
                <button id="cancelExtractTextModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="confirmExtractText" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Extract</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topicName = @json($topic->name);
            const topicId = @json($topic->topic_id);
            const reviewerText = @json($reviewerText);
            const rawText = @json($rawText);

            const toggleButton = document.getElementById('toggleButton');
            const reviewer = document.querySelector('.Reviewer');
            const rawtext = document.querySelector('.Rawtext');
            const downloadButton = document.getElementById('downloadReviewer');
            const quizbutton = document.getElementById('quiz');

            fetch('/disect_reviewer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    topicId: topicId
                })
            })
            .then(response => response.json())
            .then(data => {
                let content = '';
                if(data.success){

                    console.log('Success:', data);
                    items = data['data'];
                    
    
                    items.forEach((item) => {
                        content += `<h2 class="font-bold">${item[0]}  <span class=" font-normal"> ${item[1]}<br><br></span></h2>`;
                    });
    
                    document.querySelector('.reviewer_holder').innerHTML = content;
                }else{
                    toggleButton.classList.add('pointer-events-none'); // Works for <a> too
                    downloadButton.setAttribute('disabled', 'true');
                    downloadButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                    quizbutton.setAttribute('disabled', 'true');
                    quizbutton.classList.add('bg-gray-400', 'cursor-not-allowed');
                    content += `<h2 class="font-semibold text-red-500 text-center mt-10">${data.message} </h2>`;
                    document.querySelector('.reviewer_holder').innerHTML = content;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

            // Handle switching between raw text and reviewer text

            toggleButton.addEventListener('click', function() {
                reviewer.classList.toggle('hidden');
                toggleButton.textContent = reviewer.classList.contains('hidden') ? 'back' : 'Raw Text';
                rawtext.classList.toggle('hidden');
                toggleButton.textContent = rawtext.classList.contains('hidden') ? 'Raw Text' : 'Reviewer';
            });

            //for quiz button
            quizbutton.addEventListener('click', function(){
                window.location.href=`/quiz?topicId=${topicId}`;
            });

            // This handles the download Reviewer
            downloadButton.addEventListener('click', function() {
                const reviewerContent = document.querySelector('.reviewer_holder').innerText;
                const topicId = @json($topic->topic_id);

                const blob = new Blob([reviewerContent], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `${topicId}.txt`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            });
         
        });
    </script>
</x-layout>
