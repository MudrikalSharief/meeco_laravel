<x-layout>
    <div class="flex items-center">
        <a href="{{ route('subject') }}">
            <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects</h1>
        </a>
        <a href="#">
            <h2 class="font-semibold text-xl text-blue-500"> > Topics</h2>
        </a>
    </div>

    <div class="max-w-3xl mx-auto p-6 bg-white  rounded-lg ">
        <!-- Buttons -->
        <div class="flex gap-2 space-x-4 mb-6">
            <button class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">Reviewer</button>
            <button id="quiz" class="py-2 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">Quizzes</button>
        </div>

        <!-- Content Header -->
        <hr class="my-3">
        <div class="flex items-center justify-between mb-4">
            <h1 class="TITLE text-xl font-bold text-gray-800">Topic Name: {{ $topic->name }}</h1>
            <button id="toggleButton" class="text-blue-500 text-sm font-medium rounded-lg hover:underline">Raw text</button>
        </div>

        <!-- Scrollable Content Box -->
        <div class="Reviewer border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            {{-- Reviewer  in here --}}
            <h1>Reviewer for Topic: {{ $topic->name }}</h1>
            <h1 class="reviewer_holder"></h1>
        </div>

        <div class="Rawtext  border hidden border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            {{-- Raw text in here --}}
            <h1 class="rawtext_holder">Raw Text: {{ $rawText }}</h1>
         </div>

         <div class="Questions border hidden border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
    
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

            fetch('/disect_reviewer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reviewerText: reviewerText
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                
                let content = '';

                data.forEach((subjectData) => {
                    content += `<h2>Subject: ${subjectData.subject}</h2>`;
                    //This code below will show the name of the card
                    // subjectData.cards.forEach((card, index) => {
                    //     content += `<p>Card ${index + 1}: ${card}</p>`;
                    // });
                    subjectData.cards.forEach((card) => {
                        content += `<p><br>- ${card},</p>`;
                    });
                });

                document.querySelector('.reviewer_holder').innerHTML = content;
            })
            .catch(error => {
                console.error('Error:', error);
            });

            // Handle switching between raw text and reviewer text
            const toggleButton = document.getElementById('toggleButton');
            const reviewer = document.querySelector('.Reviewer');
            const rawtext = document.querySelector('.Rawtext');

            toggleButton.addEventListener('click', function() {
                reviewer.classList.toggle('hidden');
                toggleButton.textContent = reviewer.classList.contains('hidden') ? 'Reviewer' : 'Raw Text';
                rawtext.classList.toggle('hidden');
                toggleButton.textContent = rawtext.classList.contains('hidden') ? 'Raw Text' : 'Reviewer';
            });

            const quizbutton = document.getElementById('quiz');
            quizbutton.addEventListener('click', function(){
                window.location.href=`/quiz?topicId=${topicId}`;
            });


        });
    </script>
</x-layout>
