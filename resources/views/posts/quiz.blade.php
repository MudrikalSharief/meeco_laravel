<x-layout>
    
    <div class="max-w-2xl h-full mx-auto pt-2   rounded-lg">
        
        <div class="w-full max-w-2xl ">
            <!-- Content Header -->
            <div class="flex items-center justify-between mb-4 mx-5">
              <h1 class="TITLE text-2xl font-bold text-gray-800">Topic : <span id="topic_name"></span></h1>
            </div>
            
             <!-- Buttons -->
            <div class="flex space-x-2 mx-5 mb-2">
                <button id="reviewer" class="px-2 py-1 bg-gray-100 font-semibold text-gray-700 text-sm rounded-sm hover:bg-gray-300">Reviewer</button>
                <button id="quiz" class=" px-2 py-1  bg-blue-100 text-sm text-blue-700  rounded-sm  hover:bg-blue-300">Quizzes</button>
            </div>
            <hr class="mb-2">
            {{-- This code will show the quiz menu AND DROPDOWN --}}
            <div id="quiz_menu_holder" class=" h-full w-full px-6 py-3">
                    <div class="flex justify-between items-center">
                        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Quiz</h1>
                        <button id="addQuizButton" class=" bg-blue-500 text-white py-2 px-4 rounded">New Quiz</button>
                    </div>
        
                    {{-- this is for the title,type,and score --}}
                    <div class="px-2 flex justify-between mb-2">
                        <p class=" text-gray-500 font-semibold text-sm w-2/5">Title</p>
                        <div class="flex justify-between w-3/5">
                            <p class=" text-gray-500 font-semibold text-sm">Type</p>
                            <div class="flex justify-between w-1/2 gap-1">
                                <p class=" text-gray-500 font-semibold text-sm">Score</p>
                                <p class=" text-gray-500 font-semibold text-sm">Action</p>
                            </div>
                        </div>
                    </div>

        
                <div id="quizContainer" class="w-full max-w-2xl">
                    {{-- Question is here --}}
                </div>
            </div>
            
           
            
    </div>
   
        <!-- Delete Topic Confirmation Modal -->
        <div id="deleteTopicConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded shadow-md">
                <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
                <p>Are you sure you want to delete this Quiz?</p>
                <div class="flex justify-end mt-4">
                    <button id="cancelTopicDelete" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancel</button>
                    <button id="confirmTopicDelete" class="bg-red-500 text-white py-2 px-4 rounded">Delete</button>
                </div>
            </div>
        </div>

    {{-- Select quiz type Modal --}}
    <div id="addQuizModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center  ">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px; max-width: 400px;">
            <h2 class=" text-center text-lg font-semibold mb-4 text-blue-700">Setup Quiz</h2>

            <div class="mb-4">
                <label for="newQuizName" class="block text-xs  text-gray-600 mb-1">Quiz Name</label>
                <input id="newQuizName" type="text" placeholder="Enter the name of the Quiz" class="py-1 px-2 block w-full text-sm text-black-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="quiztype" class="block text-xs  text-gray-600 mb-1">Select Quiz Type</label>
                <select name="quiztype" id="quiztype" class=" w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                    <option value="Multiple Choice">Multiple Choice</option>
                    <option value="Identification">Identification</option>
                    <option value="True or false">True or false</option>
                    <option value="Mixed">Mixed</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="quiznumber" class="quiznumber_label block text-xs  text-gray-600 mb-1">Number of Questions</label>
                <select name="quiznumber" id="quiznumber" class=" w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                    <option value="10">10 Questions</option>
                    <option value="20">20 Questions</option>
                    <option value="30">30 Questions</option>
                    <option value="40">40 Questions</option>
                </select>
            </div>

            <div id="quiznumber_multiple_holder" class="mb-4 hidden">
                <label for="quiznumber_multiple" class="block text-xs text-gray-600 mb-1">Multiple Choice</label>
                <div class="flex gap-2">
                    <select name="quiznumber_multiple" id="quiznumber_multiple" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="5">5 Questions</option>
                        <option value="10">10 Questions</option>
                        <option value="15">15 Questions</option>
                        <option value="20">20 Questions</option>
                    </select>
                    <button id="removeMultipleChoice" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">x</button>
                </div>
            </div>
            
            <div id="quiznumber_true_or_false_holder" class="mb-4 hidden">
                <label for="quiznumber_true_or_false" class="block text-xs text-gray-600 mb-1">True or False</label>
                <div class="flex gap-2">
                    <select name="quiznumber_true_or_false" id="quiznumber_true_or_false" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="5">5 Questions</option>
                        <option value="10">10 Questions</option>
                        <option value="15">15 Questions</option>
                        <option value="20">20 Questions</option>
                    </select>
                    <button id="removeTrueOrFalse" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">x</button>
                </div>
            </div>
            
            <div id="quiznumber_identification_holder" class="mb-4 hidden">
                <label for="quiznumber_identification" class="block text-xs text-gray-600 mb-1">Identification</label>
                <div class="flex gap-2">
                    <select name="quiznumber_identification" id="quiznumber_identification" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="5">5 Questions</option>
                        <option value="10">10 Questions</option>
                        <option value="15">15 Questions</option>
                        <option value="20">20 Questions</option>
                    </select>
                    <button id="removeIdentification" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">x</button>
                </div>    
            </div>

            {{-- //ad quiztype button --}}
            <button id="addQuizTypeButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hidden">Add Quiz Type</button>

            <div class="flex justify-end mt-4">
                <button id="cancelQuizButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="saveQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </div>
    </div>
    

    {{-- this is the modal when slecting a quiz type in --}}
    <div id="selectQuizTypeModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center  ">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px; max-width: 400px;">
            <h2 class="text-center text-lg font-semibold mb-4 text-blue-700">Select Quiz Type</h2>
            <div class="flex flex-col space-y-2">
                <button id="selectMultipleChoice" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Multiple Choice</button>
                <button id="selectTrueOrFalse" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">True or False</button>
                <button id="selectIdentification" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Identification</button>
            </div>
            <div class="flex justify-end mt-4">
                <button id="closeSelectQuizTypeModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-center text-lg font-semibold mb-4 text-blue-700">Success</h2>
            <p class="text-center mb-4">Quiz created successfully!</p>
            <div class="flex justify-center">
                <button id="closeSuccessModalButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px;">
            <h2 class="text-center text-lg font-semibold mb-4 text-red-700">Error</h2>
            <p id="errorMessage" class="text-center mb-4"></p>
            <div class="flex justify-center">
                <button id="closeErrorModalButton" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>    

    </div>
    
    {{-- Loader --}}
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="loader"></div>
    </div>
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // this will go to the reviewer page
        const urlParams = new URLSearchParams(window.location.search);
        const topicId = urlParams.get('topicId');

        const topic_name_container = document.getElementById('topic_name');

    fetch(`/getTopicByTopicId/${topicId}`)
    .then(response => response.json())
    .then( data => {
        $name = data.name;
        topic_name_container.innerHTML = $name;
    }).catch(error => console.error('error :: ', error));
    
    // this code is esponsible for getting all the quiz
    const quizContainer = document.getElementById('quizContainer');
    if (!quizContainer) {
        console.error('Quiz container not found');
        return;
    }else{
       // Fetch quizzes and render them
       fetch(`/getquizzes/${topicId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.questions.forEach(quiz => {
                        const button = document.createElement('button');
                        button.classList.add('question_button', 'gap-1', 'w-full', 'bg-blue-50','text-start', 'text-xs', 'sm:text-sm', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-200', 'delay-75', 'hover:shadow-lg', 'transition', 'duration-300');
                        button.id = quiz.question_id;
                        button.innerHTML = `
                            <p class="w-2/5 ">${quiz.question_title}</p>
                            <div class="flex justify-between w-3/5">
                                <p class="text-xs sm:text-sm items-center">${quiz.question_type}</p>
                                <div class="flex justify-between w-1/2 gap-1">
                                    <p class="w-2/5 flex item-center text-green-500 items-center"> ${quiz.score}/${quiz.number_of_question}</p>
                                    <div class="flex gap-1 justify-end items-center w-2/5">
                                        <img class="hidden w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/edit.png" alt="edit">
                                        <img class="delete-button w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete" data-question-id="${quiz.question_id}">
                                    </div>
                                </div>
                            </div>
                        `;
                        quizContainer.appendChild(button);
                    });
                } else {
                    const NoQuestions = document.createElement('p');
                    NoQuestions.classList.add('text-center', 'text-red-500', 'text-lg', 'py-4');
                    NoQuestions.innerHTML = 'No quizzes found.';
                    quizContainer.appendChild(NoQuestions);
                }
            });
    }
    

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
        const quiznumber_label = document.querySelector(".quiznumber_label");
        const quiznumber_multiple_holder = document.getElementById('quiznumber_multiple_holder');
        const quiznumber_true_or_false_holder = document.getElementById('quiznumber_true_or_false_holder');
        const quiznumber_identification_holder = document.getElementById('quiznumber_identification_holder');
        const quiznumber_multiple = document.getElementById('quiznumber_multiple');
        const quiznumber_true_or_false = document.getElementById('quiznumber_true_or_false');
        const quiznumber_identification= document.getElementById('quiznumber_identification');



        //show the mixed creation of quiz
        quiztype.addEventListener('change', function() {
            if (quiztype.value == "Mixed") {
                quiznumber.classList.add('hidden');
                quiznumber.setAttribute('disabled', 'true');
                // quiznumber_label.classList.remove('text-xs');
                // quiznumber_label.classList.add('text-sm');
                // quiznumber_label.classList.add('font-semibold');
                quiznumber_label.classList.add('hidden');
                addQuizTypeButton.classList.remove('hidden');
                quiznumber_multiple_holder.classList.add('hidden');
                quiznumber_true_or_false_holder.classList.add('hidden');
                quiznumber_identification_holder.classList.add('hidden');
            } else {
                quiznumber.classList.remove('hidden');
                quiznumber.removeAttribute('disabled');
                // quiznumber_label.classList.remove('text-sm');
                // quiznumber_label.classList.add('text-xs');
                // quiznumber_label.classList.remove('font-semibold');
                quiznumber_label.classList.remove('hidden');
                addQuizTypeButton.classList.add('hidden');
                quiznumber_multiple_holder.classList.add('hidden');
                quiznumber_true_or_false_holder.classList.add('hidden');
                quiznumber_identification_holder.classList.add('hidden');
            }
        });

            const totalQuizTypes = 3; // Number of quiz types: multiple, true_or_false, identification
            const addedQuizTypes = new Set();

            const addQuizTypeButton = document.getElementById('addQuizTypeButton');
            const selectQuizTypeModal = document.getElementById('selectQuizTypeModal');
            const closeSelectQuizTypeModal = document.getElementById('closeSelectQuizTypeModal');
            const selectMultipleChoice = document.getElementById('selectMultipleChoice');
            const selectTrueOrFalse = document.getElementById('selectTrueOrFalse');
            const selectIdentification = document.getElementById('selectIdentification');
            const removeMultipleChoice = document.getElementById('removeMultipleChoice');
            const removeTrueOrFalse = document.getElementById('removeTrueOrFalse');
            const removeIdentification = document.getElementById('removeIdentification');

            addQuizTypeButton.addEventListener('click', function() {
                if (!addedQuizTypes.has('multiple')) {
                    selectMultipleChoice.classList.remove('hidden');
                }
                if (!addedQuizTypes.has('true_or_false')) {
                    selectTrueOrFalse.classList.remove('hidden');
                }
                if (!addedQuizTypes.has('identification')) {
                    selectIdentification.classList.remove('hidden');
                }
                selectQuizTypeModal.classList.remove('hidden');
            });

            closeSelectQuizTypeModal.addEventListener('click', function() {
                selectQuizTypeModal.classList.add('hidden');
            });

            selectMultipleChoice.addEventListener('click', function() {
                quiznumber_multiple_holder.classList.remove('hidden');
                selectQuizTypeModal.classList.add('hidden');
                addedQuizTypes.add('multiple');
                selectMultipleChoice.classList.add('hidden');
                if (addedQuizTypes.size === totalQuizTypes) {
                    addQuizTypeButton.classList.add('hidden');
                }
            });

            selectTrueOrFalse.addEventListener('click', function() {
                quiznumber_true_or_false_holder.classList.remove('hidden');
                selectQuizTypeModal.classList.add('hidden');
                addedQuizTypes.add('true_or_false');
                selectTrueOrFalse.classList.add('hidden');
                if (addedQuizTypes.size === totalQuizTypes) {
                    addQuizTypeButton.classList.add('hidden');
                }
            });

            selectIdentification.addEventListener('click', function() {
                quiznumber_identification_holder.classList.remove('hidden');
                selectQuizTypeModal.classList.add('hidden');
                addedQuizTypes.add('identification');
                selectIdentification.classList.add('hidden');
                if (addedQuizTypes.size === totalQuizTypes) {
                    addQuizTypeButton.classList.add('hidden');
                }
            });

            removeMultipleChoice.addEventListener('click', function() {
                quiznumber_multiple_holder.classList.add('hidden');
                addedQuizTypes.delete('multiple');
                selectMultipleChoice.classList.remove('hidden');
                addQuizTypeButton.classList.remove('hidden');
            });

            removeTrueOrFalse.addEventListener('click', function() {
                quiznumber_true_or_false_holder.classList.add('hidden');
                addedQuizTypes.delete('true_or_false');
                selectTrueOrFalse.classList.remove('hidden');
                addQuizTypeButton.classList.remove('hidden');
            });

            removeIdentification.addEventListener('click', function() {
                quiznumber_identification_holder.classList.add('hidden');
                addedQuizTypes.delete('identification');
                selectIdentification.classList.remove('hidden');
                addQuizTypeButton.classList.remove('hidden');
            });

            
        //remove the error button
        
        closeErrorModalButton.addEventListener('click', function() {
            errorModal.classList.add('hidden');
        });

        saveQuizButton.addEventListener('click', function() {
            const QuizName = newQuizName.value.trim();
            const QuizType = quiztype.value;
            const QuizNumber = quiznumber.value;
            let quiznumber_multiple_value = 0;
            let quiznumber_true_or_false_value = 0;
            let quiznumber_identification_value = 0;

            if (!QuizName) {
                errorMessage.textContent = 'Please enter a name for the quiz.';
                errorModal.classList.remove('hidden');
                return;
            }

            if(QuizType !== 'Mixed'){
                console.log("Hallloooo");
                quiznumber_multiple_value = 0;
                quiznumber_true_or_false_value = 0 ;
                quiznumber_identification_value = 0 ;
            }else{
                let selectedQuizTypes = 0;
                if (!quiznumber_multiple_holder.classList.contains('hidden')) {
                    quiznumber_multiple_value = quiznumber_multiple.value;
                    selectedQuizTypes++;
                }
                if (!quiznumber_true_or_false_holder.classList.contains('hidden')) {
                    quiznumber_true_or_false_value = quiznumber_true_or_false.value;
                    selectedQuizTypes++;
                }
                if (!quiznumber_identification_holder.classList.contains('hidden')) {
                    quiznumber_identification_value = quiznumber_identification.value;
                    selectedQuizTypes++;
                }

                //show an error if the quiz type is less than two quizzes
                if (selectedQuizTypes < 2) {
                    errorMessage.textContent = 'Please select at least two quiz types for a mixed quiz.';
                    errorModal.classList.remove('hidden');
                    return;
                }
            }



            // Show the loader
            loader.classList.remove('hidden');

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
                    multiple: quiznumber_multiple_value,
                    true_or_false: quiznumber_true_or_false_value,  
                    identification: quiznumber_identification_value
                }),
            })
            .then(response => response.json())
            .then(data => {
                 // Hide the loader
                 loader.classList.add('hidden');
                if (data.success) {
                    addQuizModal.classList.add('hidden');
                    // Show the success modal
                    successModal.classList.remove('hidden');

                    //generate the quiz card again
                    fetch(`/getquizzes/${topicId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            
                            quizContainer.innerHTML="";
                            data.questions.forEach(quiz => {
                                const button = document.createElement('button');
                                button.classList.add('question_button','gap-1','w-full', 'text-start', 'text-xs', 'sm:text-sm', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-50', 'delay-75',  'hover:shadow-lg', 'transition', 'duration-300');
                                button.id = quiz.question_id;
                                button.innerHTML = `
                                    <p class="w-2/5 ">${quiz.question_title}</p>
                                    <div class="flex justify-between w-3/5">
                                        <p class="text-xs sm:text-sm items-center">${quiz.question_type}</p>
                                        <div class="flex justify-between w-1/2  gap-1">
                                            <p class="w-2/5 flex item-center text-green-500 items-center"> ${quiz.score}/${quiz.number_of_question}</p>
                                            <div class="flex gap-1 items-center w- 3/5">
                                                <img class="hidden w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/edit.png" alt="delete">
                                                <img class="delete-button w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete" data-question-id="${quiz.question_id}">
                                            </div>
                                        </div>    
                                    </div>
                                `;
                                quizContainer.appendChild(button);
                            });
                            // Reset form values
                            newQuizName.value = '';
                            quiztype.value = 'Multiple Choice';
                            quiznumber.value = '10';
                            quiznumber_multiple_holder.classList.add('hidden');
                            quiznumber_true_or_false_holder.classList.add('hidden');
                            quiznumber_identification_holder.classList.add('hidden');
                            addedQuizTypes.clear();
                            addQuizTypeButton.classList.add('hidden');
                            // location.reload();
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




        const opened_quizz_holder = document.getElementById('opened_quizz_holder');
        const quiz_menu_holder = document.getElementById('quiz_menu_holder');
        const deleteTopicConfirmModal = document.getElementById('deleteTopicConfirmModal');
        const confirmTopicDelete = document.getElementById('confirmTopicDelete');

            // Event delegation for delete buttons
            quizContainer.addEventListener('click', function(event) {
            const deleteButton = event.target.closest('.delete-button');
                                
            const button = event.target.closest('.question_button');
            
            if (deleteButton) {
                console.log('delete button clicked')
                deleteTopicConfirmModal.classList.remove('hidden');
                event.stopPropagation(); // Prevent the event from propagating to the parent elements
                event.preventDefault(); // Prevent the default action
                //check if confirm modal is deleted confirm the delete
                confirmTopicDelete.addEventListener('click', function(){
                    const questionId = deleteButton.dataset.questionId;
                    console.log(questionId)

                    fetch(`/deletequiz/${questionId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the quiz button from the DOM
                            deleteButton.closest('.question_button').remove();
                            deleteTopicConfirmModal.classList.add('hidden');
                            if (quizContainer.children.length === 0) {
                                const NoQuestions = document.createElement('p');
                                NoQuestions.classList.add('text-center', 'text-red-500', 'text-lg', 'py-4');
                                NoQuestions.innerHTML = 'No quizzes found.';
                                quizContainer.appendChild(NoQuestions);
                            }
                        } else {
                            deleteTopicConfirmModal.classList.add('hidden');
                            alert('Failed to delete quiz: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                    
                })
                
            }else if(button) {
                // Event delegation for question buttons
                console.log("question is clicked");
                const questionId = button.id;
                window.location.href = `/quizresult?questionId=${questionId}`;
            }
        });


        const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');
        closeSuccessModalButton.addEventListener('click', function() {
            successModal.classList.add('hidden');
        });
        
});

</script>

</x-layout>