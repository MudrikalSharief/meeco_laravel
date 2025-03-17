<x-layout>
    
    <div class="reviewer_whole_content max-w-2xl mx-auto pt-2 rounded-lg">
   
        
        <!-- Content Header -->
        <div class="flex items-center justify-between mb-4 mx-5">
            <h1 class="TITLE text-2xl font-bold text-gray-800"><a href="/subjects/{{ $topic->subject_id }}">&larr;</a> Topic : {{ $topic->name }}</h1>
        </div>
    
        <!-- Buttons -->
        <div class="flex space-x-2 mx-5 mb-2 ">
            <button class="px-2 py-1 bg-blue-400 font-medium text-sm text-gray-50 rounded-sm cursor-default">Reviewer</button>
            <button id="quiz" class=" px-2 py-1 bg-gray-100 text-sm text-gray-700  rounded-sm  hover:bg-gray-300">Quizzes</button>
        </div>
        <hr class="mb-2">
        {{-- rawtext-reviewer --}}
        <div class="mx-5 flex justify-end gap-2 items-center">
            <button id="toggleButton" class="px-2 py-1 bg-blue-200 text-sm flex gap-1  rounded-sm hover:bg-blue-400  items-center">
                <span id="viewRawOrRev" class="text-black-500 text-xs">Reviewer</span>
                <div>
                    <img class="max-w-4 eye1" src="{{ asset('logo_icons/eye1.svg') }}" alt="view">    
                    <img class="max-w-4 hidden eye2" src="{{ asset('logo_icons/eye2.svg') }}" alt="view">  
                </div>
            </button>
            <button id="cardReviwe" class="flex px-2 py-1 bg-green-100 text-sm text-green-700 rounded-sm hover:bg-green-300 items-center">
                <img class="max-w-4" src="{{ asset('logo_icons/memo.svg') }}" alt="download">    
            </button>
            <button id="downloadReviewer" class="flex px-2 py-1 bg-gray-100 text-smrounded-sm hover:bg-gray-300 items-center">
                <img class="max-w-4" src="{{ asset('logo_icons/download.svg') }}" alt="download">    
            </button>
        </div>

        <!-- Scrollable Content Box -->
        <div class="Reviewer mx-5 my-3 rounded-lg";>
            {{-- Reviewer  in here --}}
            <h1 class="reviewer_holder"></h1>
        </div>

        <div class="Rawtext hidden mx-5 my-3 rounded-lg";>
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
            const cardReviwe = document.getElementById('cardReviwe');
            const quizbutton = document.getElementById('quiz');
            const viewRawOrRev = document.getElementById('viewRawOrRev');
            const eye1 = document.querySelector('.eye1');
            const eye2 = document.querySelector('.eye2');

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
                    cardReviwe.setAttribute('disabled', 'true');
                    cardReviwe.classList.add('bg-gray-400', 'cursor-not-allowed');
                    
                    content += `<h2 class="font-semibold text-red-500 text-center mt-10">${data.message} </h2>`;
                    document.querySelector('.reviewer_holder').innerHTML = content;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

            //click event for cards
            cardReviwe.addEventListener('click', function(){
                window.location.href=`/cards?topicId=${topicId}`;
            });

            // Handle switching between raw text and reviewer text

            toggleButton.addEventListener('click', function() {
                reviewer.classList.toggle('hidden');
                rawtext.classList.toggle('hidden');
                if(reviewer.classList.contains('hidden')){
                    eye1.classList.add('hidden');
                    eye2.classList.remove('hidden');
                    viewRawOrRev.innerText = 'Raw Text';
                }else{
                    eye2.classList.add('hidden');
                    eye1.classList.remove('hidden');
                    viewRawOrRev.innerText = 'Reviewer';
                }
                
            });

            //for quiz button
            quizbutton.addEventListener('click', function(){
                window.location.href=`/quiz?topicId=${topicId}`;
            });

            // This handles the download Reviewer
            downloadButton.addEventListener('click', function() {
                const reviewerContent = document.querySelector('.reviewer_holder').innerHTML;
                const topicId = @json($topic->topic_id);
                console.log(reviewerContent);
                fetch('/download-pdf', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        topicId: topicId,
                        reviewerContent: reviewerContent
                    })
                })
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `${topicId}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error:', error));
            });
         
        });
    </script>
</x-layout>
