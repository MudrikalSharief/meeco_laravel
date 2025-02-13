<x-layout>
    
    <div class="max-w-2xl h-full mx-auto pt-6 bg-white  rounded-lg">
        
        <div class="w-full max-w-2xl">

            <div class="flex gap-2 space-x-4 mb-6">
                <button id="reviewer" class="py-2 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">Reviewer</button>
                <button id="quiz" class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">Quizzes</button>
            </div>
            <hr class="my-3">

            {{-- This code will show the quiz menu AND DROPDOWN --}}
            <div id="quiz_menu_holder" class="hidden">
                    <div class="flex justify-between items-center">
                        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Quiz</h1>
                        <button id="addQuizButton" class=" bg-blue-500 text-white py-2 px-4 rounded">New Quiz</button>
                    </div>
        
                    {{-- this is for the title,type,and score --}}
                    <div class="px-6 flex justify-between mb-2">
                        <p class=" text-gray-500 font-semibold text-sm">Title</p>
                        <div class="flex justify-between w-5/12">
                            <p class=" text-gray-500 font-semibold text-sm">Type</p>
                            <p class=" text-gray-500 font-semibold text-sm">Score</p>
                        </div>
                    </div>
        
                <div id="quizContainer" class="w-full max-w-2xl">
                    {{-- Question is here --}}
                </div>
            </div>
            
            <div id="opened_quizz_holder" class=" bg-blue-50 h-full w-full">
                hi
            </div>

    </div>
   
    {{-- Select quiz type Modal --}}
    <div id="addQuizModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center  ">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class=" text-center text-lg font-semibold mb-4 text-blue-700">Setup Quiz</h2>
            <div class="mb-4">
                <label for="quiztype" class="block text-sm font-medium text-gray-700 mb-1">Select Quiz Type</label>
                <select name="quiztype" id="quiztype" class=" w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                    <option value="0">Multiple Choice</option>
                    <option value="1">Identification</option>
                    <option value="2">True or false</option>
                    <option value="3">Mixed</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="newQuizName" class="block text-sm font-medium text-gray-700 mb-1">Quiz Name</label>
                <input id="newQuizName" type="text" placeholder="Enter the name of the Quiz" class="p-1 block w-full text-sm text-black-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="quiznumber" class="block text-sm font-medium text-gray-700 mb-1">Number of Questions</label>
                <select name="quiznumber" id="quiznumber" class=" w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
            </div>
            <div class="flex justify-end mt-4">
                <button id="cancelQuizButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="saveQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </div>
    </div>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

    // this code is esponsible for getting all the quiz
    const quizContainer = document.getElementById('quizContainer');
    if (!quizContainer) {
        console.error('Quiz container not found');
        return;
    }else{
        fetch('/getquizzes')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.questions.forEach(quiz => {
                    const button = document.createElement('button');
                    button.classList.add('w-full', 'text-start', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-50', 'delay-75', 'hover:transform', 'hover:-translate-y-1', 'hover:shadow-lg', 'transition', 'duration-300');
                    button.innerHTML = `
                        <p class=" ">${quiz.question_title}</p>
                        <div class="flex justify-between w-1/2">
                            <p class="">${quiz.question_type}</p>
                            <p class=""> ${quiz.score} / ${quiz.number_of_question}</p>
                        </div>
                    `;
                    quizContainer.appendChild(button);
                });
            } else {
                alert('Failed to get quizzes: ' + data.message);
            }

        });
    }
    


    // this will go to the reviewer page
        const urlParams = new URLSearchParams(window.location.search);
        const topicId = urlParams.get('topicId');

        const reviewer = document.getElementById('reviewer');
        if(reviewer){
            reviewer.addEventListener('click', function(){
                window.location.href=`/reviewer/${topicId}`;
            });
        }

    // this code will run after the new quiz is clicked
        const newQuizButton = document.getElementById('addQuizButton');
        const addQuizModal = document.getElementById('addQuizModal');
        const cancelQuizButton = document.getElementById('cancelQuizButton');

        
        if (!newQuizButton) {
            console.error('Button not found');
            return;
        }

        newQuizButton.addEventListener('click', function() {
            addQuizModal.classList.remove('hidden');

        });

        cancelQuizButton.addEventListener('click', function() {
            addQuizModal.classList.add('hidden');
        });

    //this code work when the save button is clicked
        const saveQuizButton = document.getElementById('saveQuizButton');
        const newQuizName = document.getElementById('newQuizName');
        const quiztype = document.getElementById('quiztype');
        const quiznumber = document.getElementById('quiznumber');

        saveQuizButton.addEventListener('click', function() {
            const QuizName = newQuizName.value.trim();
            const QuizType = quiztype.value;
            const QuizNumber = quiznumber.value;

            if (!QuizName) {
                alert('Please enter a name for the quiz.');
                return;
            }

            fetch(`/generate-quiz/${topicId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: QuizName,
                    type: QuizType,
                    number: QuizNumber,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addQuizModal.classList.add('hidden');
                    alert('Success to create quiz.');

                    //generate the quiz card again
                    fetch('/getquizzes')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            quizContainer.innerHTML="";
                            data.questions.forEach(quiz => {
                                const button = document.createElement('button');
                                button.classList.add('w-full', 'text-start', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-50', 'delay-75', 'hover:transform', 'hover:-translate-y-1', 'hover:shadow-lg', 'transition', 'duration-300');
                                button.innerHTML = `
                                    <p class=" ">${quiz.question_title}</p>
                                    <div class="flex justify-between w-1/2">
                                        <p class="">${quiz.question_type}</p>
                                        <p class=""> ${quiz.score} / ${quiz.number_of_question}</p>
                                    </div>
                                `;
                                quizContainer.appendChild(button);
                            });
                        } else {
                            alert('Failed to get quizzes: ' + data.message);
                        }
                    });       
                } else {
                    alert('Failed to create quiz: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
        
});

</script>

</x-layout>