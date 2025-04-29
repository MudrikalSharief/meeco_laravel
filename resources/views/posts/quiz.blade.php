<x-layout>
    
    <div class="max-w-2xl h-full mx-auto pt-2   rounded-lg">
        
        <div class="w-full max-w-2xl ">
            <!-- Content Header -->
            <div class="flex items-center justify-between mb-4 mx-5">
              <h1 class="TITLE text-2xl font-bold text-gray-800"><span id="back_to_subject" class=" cursor-pointer">&larr;</span> Topic : <span id="topic_name"></span></h1>
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
    <div id="addQuizModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-0 max-h-[80vh] overflow-y-auto" style="width: 50%; min-width: 270px; max-width: 400px;">
            
            <div class="px-4">
            <div class="sticky top-0 bg-white z-10 border-b w-full rounded-t-lg">
                <h2 class="text-center text-lg font-semibold text-blue-700 px-4 py-4">Setup Quiz</h2>
            </div>
            <div class="px-4 overflow-y-auto">
            <div class="mb-4">
                <label for="newQuizName" class="block text-xs text-gray-600 mb-1">Quiz Name</label>
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
                <label for="difficulty" class="block text-xs text-gray-600 mb-1">Select Difficulty</label>
                <select name="difficulty" id="difficulty" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
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
                <div class="mt-1">
                    <label for="difficulty_multiple" class="block text-xs text-gray-600 mb-1">Difficulty</label>
                    <select name="difficulty_multiple" id="difficulty_multiple" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
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
                <div class="mt-1">
                    <label for="difficulty_true_or_false" class="block text-xs text-gray-600 mb-1">Difficulty</label>
                    <select name="difficulty_true_or_false" id="difficulty_true_or_false" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
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
                <div class="mt-1">
                    <label for="difficulty_identification" class="block text-xs text-gray-600 mb-1">Difficulty</label>
                    <select name="difficulty_identification" id="difficulty_identification" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            {{-- //ad quiztype button --}}
            <button id="addQuizTypeButton" class="bg-blue-500 text-white px-4 py-2 mb-4 rounded hover:bg-blue-600 hidden">Add Quiz Type</button>
            </div>
            <div class="flex justify-end mt-4 sticky bottom-0 bg-white px-4 pt-2 pb-3 z-10 border-t">
                <button id="cancelQuizButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                <button id="saveQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            </div>
        </div>
    </div>
    </div>
    

    {{-- this is the modal when selecting a quiz type in --}}
    <div id="selectQuizTypeModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-4 max-h-[80vh] overflow-y-auto" style="width: 50%; min-width: 270px; max-width: 400px;">
            <h2 class="text-center text-lg font-semibold mb-4 text-blue-700 sticky top-0 bg-white p-2">Select Quiz Type</h2>
            <div class="flex flex-col space-y-2">
                <button id="selectMultipleChoice" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Multiple Choice</button>
                <button id="selectTrueOrFalse" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">True or False</button>
                <button id="selectIdentification" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Identification</button>
            </div>
            <div class="flex justify-end mt-4 sticky bottom-0 bg-white pt-2 pb-1">
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

    <x-confirmation_modal id="dynamicModal" title="" titleColor="" message="" buttonId="dynamicModalButton" buttonText="OK" />

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

<div id="editQuizModal" class="fixed hidden inset-0 z-50 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-4" style="width: 50%; min-width: 270px; max-width: 400px;">
        <h2 class="text-center text-lg font-semibold mb-4 text-blue-700">Edit Quiz Name</h2>
        <div class="mb-4">
            <label for="editQuizName" class="block text-xs text-gray-600 mb-1">Quiz Name</label>
            <input id="editQuizName" type="text" placeholder="Enter the new name of the Quiz" class="py-1 px-2 block w-full text-sm text-black-500 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex justify-end mt-4">
            <button id="cancelEditQuizButton" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
            <button id="saveEditQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const imageContainer = document.getElementById('imageContainer');
        const modal = document.getElementById('dynamicModal');
        const modalTitle = document.getElementById('dynamicModal-title');
        const modalMessage = document.getElementById('dynamicModal-message');
        const modalButton = document.getElementById('dynamicModalButton');
        const successModal = document.getElementById('successModal');
        const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');

        if (closeSuccessModalButton) {
            closeSuccessModalButton.addEventListener('click', function() {
                successModal.classList.add('hidden');
            });
        }

        // Function to show the dynamic modal
        function showModal(title = '', message = '', titleColor = '', buttonText = '') {
            modalTitle.textContent = title;
            modalTitle.className = `text-lg font-semibold mb-4 ${titleColor}`;
            modalMessage.textContent = message;
            modalButton.textContent = buttonText;
            modal.classList.remove('hidden');
        }

             // Close the modal when the close button is clicked
                document.getElementById('dynamicModal-close').addEventListener('click', function() {
            document.getElementById('dynamicModal').classList.add('hidden');
        });


        // this will go to the reviewer page
        const urlParams = new URLSearchParams(window.location.search);
        const topicId = urlParams.get('topicId');

        
        
        

        const topic_name_container = document.getElementById('topic_name');

        const back_to_subject = document.getElementById('back_to_subject');
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
                        button.classList.add('question_button', 'gap-1', 'w-full', 'bg-white','text-start', 'text-xs', 'sm:text-sm', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-200', 'delay-75', 'hover:shadow-lg', 'transition', 'duration-300');
                        button.id = quiz.question_id;
                        button.innerHTML = `
                            <p class="w-2/5 ">${quiz.question_title}</p>
                            <div class="flex justify-between w-3/5">
                                <p class="text-xs sm:text-sm items-center">${quiz.question_type}</p>
                                <div class="flex justify-between w-1/2 gap-1">
                                    <p class="w-2/5 flex item-center text-green-500 items-center"> ${quiz.score}/${quiz.number_of_question}</p>
                                    <div class="flex gap-1 justify-end items-center w-2/5">
                                        <img class="edit-button w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/edit.png" alt="edit" data-question-id="${quiz.question_id}">
                                        <img class="delete-button w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/delete.png" alt="delete" data-question-id="${quiz.question_id}">
                                    </div>
                                </div>
                            </div>
                        `;
                        quizContainer.appendChild(button);
                    });

                    if(back_to_subject){
                        back_to_subject.addEventListener('click', function(){
                            window.location.href="/subjects/"+data.subject_id;
                        });
                    }
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

            //get the max number of quiz question
           // Fetch quiz question limit and update quiznumber options
           fetch('/subscription/get-quiz-question-limit', {
                method: 'POST',
                headers: {
                    
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const quizQuestionLimit = data.quiz_questions_limit;
                    const quiznumber = document.getElementById('quiznumber');
                    const options = quiznumber.options;

                    //this is for the option in quiz number
                    for (let i = 0; i < options.length; i++) {
                        const option = options[i];
                        console.log(option.value);
                        console.log(quizQuestionLimit);
                        if (parseInt(option.value) > quizQuestionLimit) {
                            console.log('true');
                            option.disabled = true;
                        } else {
                            console.log('false');
                            option.disabled = false;
                        }
                    }

                    //mix quiz type
                    const quiztype = document.getElementById('quiztype');
                    const mixQuizLimit = data.mixQuizLimit;
                
                    const quiznumber_true_or_false_holder = document.getElementById('quiznumber_true_or_false');
                    const tf_option = quiznumber_true_or_false_holder.options;
                    const quiznumber_identification_holder = document.getElementById('quiznumber_identification');
                    const id_option = quiznumber_identification_holder.options;
                    const quiznumber_multiple = document.getElementById('quiznumber_multiple');
                    const mc_option = quiznumber_multiple.options;

                    if(data.MixQuizType == 0){
                        quiztype.options[3].disabled = true;
                    }else{
                        quiztype.options[3].disabled = false;

                        for (let i = 0; i < tf_option.length; i++) {
                            const option = tf_option[i];
                            if (parseInt(option.value) > mixQuizLimit) {
                                option.disabled = true;
                            } else {
                                option.disabled = false;
                            }
                        }

                        for (let i = 0; i < id_option.length; i++) {
                            const option = id_option[i];
                            if (parseInt(option.value) > mixQuizLimit) {
                                option.disabled = true;
                            } else {
                                option.disabled = false;
                            }
                        }

                        for (let i = 0; i < mc_option.length; i++) {
                            const option = mc_option[i];
                            if (parseInt(option.value) > mixQuizLimit) {
                                option.disabled = true;
                            } else {
                                option.disabled = false;
                            }
                        }

                    }


                } else {
                    console.error('Failed to fetch quiz question limit:', data.message);
                }
            })
            .catch(error => console.error('Error fetching quiz question limit:', error));
        

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
                quiznumber_label.classList.add('hidden');
                addQuizTypeButton.classList.remove('hidden');
                quiznumber_multiple_holder.classList.add('hidden');
                quiznumber_true_or_false_holder.classList.add('hidden');
                quiznumber_identification_holder.classList.add('hidden');
                
                // Hide the general difficulty selector for mixed quizzes
                document.getElementById('difficulty').parentElement.classList.add('hidden');
            } else {
                quiznumber.classList.remove('hidden');
                quiznumber.removeAttribute('disabled');
                quiznumber_label.classList.remove('hidden');
                addQuizTypeButton.classList.add('hidden');
                quiznumber_multiple_holder.classList.add('hidden');
                quiznumber_true_or_false_holder.classList.add('hidden');
                quiznumber_identification_holder.classList.add('hidden');
                
                // Show the general difficulty selector for other quiz types
                document.getElementById('difficulty').parentElement.classList.remove('hidden');
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

            // Create counters for each quiz type
            let mcInstanceCounter = 0;
            let tfInstanceCounter = 0;
            let idInstanceCounter = 0;
            
            // Arrays to store instances of each quiz type
            const mcInstances = [];
            const tfInstances = [];
            const idInstances = [];
            
            addQuizTypeButton.addEventListener('click', function() {
                // Always show all quiz type options, even if they've been added before
                selectMultipleChoice.classList.remove('hidden');
                selectTrueOrFalse.classList.remove('hidden');
                selectIdentification.classList.remove('hidden');
                
                selectQuizTypeModal.classList.remove('hidden');
            });

            closeSelectQuizTypeModal.addEventListener('click', function() {
                selectQuizTypeModal.classList.add('hidden');
            });
            
            // Function to create a new instance of a quiz type
            function createQuizTypeInstance(type, instanceId) {
                const holder = document.createElement('div');
                holder.id = `${type}_holder_${instanceId}`;
                holder.className = 'mb-4';
                
                let typeName;
                switch(type) {
                    case 'multiple':
                        typeName = 'Multiple Choice';
                        break;
                    case 'true_or_false':
                        typeName = 'True or False';
                        break;
                    case 'identification':
                        typeName = 'Identification';
                        break;
                }
                
                holder.innerHTML = `
                    <label for="${type}_${instanceId}" class="block text-xs text-gray-600 mb-1">${typeName} #${instanceId + 1}</label>
                    <div class="flex gap-2">
                        <select name="${type}_${instanceId}" id="${type}_${instanceId}" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                            <option value="5">5 Questions</option>
                            <option value="10">10 Questions</option>
                            <option value="15">15 Questions</option>
                            <option value="20">20 Questions</option>
                        </select>
                        <button id="remove_${type}_${instanceId}" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">x</button>
                    </div>
                    <div class="mt-1">
                        <label for="difficulty_${type}_${instanceId}" class="block text-xs text-gray-600 mb-1">Difficulty</label>
                        <select name="difficulty_${type}_${instanceId}" id="difficulty_${type}_${instanceId}" class="w-full border border-gray-300 shadow-sm p-1 rounded-lg">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                `;
                
                // Add the new instance holder before the Add Quiz Type button
                addQuizModal.querySelector('.flex.justify-end.mt-4').before(holder);
                
                // Disable already used difficulty options for this quiz type
                const difficultySelect = document.getElementById(`difficulty_${type}_${instanceId}`);
                if (difficultySelect) {
                    // Get all used difficulties for this quiz type
                    let usedDifficulties = [];
                    if (type === 'multiple') {
                        usedDifficulties = mcInstances
                            .filter(instance => instance.id !== instanceId) // Don't include current instance
                            .map(instance => {
                                const select = document.getElementById(`difficulty_multiple_${instance.id}`);
                                return select ? select.value : null;
                            })
                            .filter(Boolean);
                    } else if (type === 'true_or_false') {
                        usedDifficulties = tfInstances
                            .filter(instance => instance.id !== instanceId)
                            .map(instance => {
                                const select = document.getElementById(`difficulty_true_or_false_${instance.id}`);
                                return select ? select.value : null;
                            })
                            .filter(Boolean);
                    } else if (type === 'identification') {
                        usedDifficulties = idInstances
                            .filter(instance => instance.id !== instanceId)
                            .map(instance => {
                                const select = document.getElementById(`difficulty_identification_${instance.id}`);
                                return select ? select.value : null;
                            })
                            .filter(Boolean);
                    }

                    // Disable options that are already used
                    Array.from(difficultySelect.options).forEach(option => {
                        if (usedDifficulties.includes(option.value)) {
                            option.disabled = true;
                        }
                    });
                }
                
                // Add event listener to the remove button
                document.getElementById(`remove_${type}_${instanceId}`).addEventListener('click', function() {
                    holder.remove();
                    
                    // Remove the instance from the appropriate array
                    if (type === 'multiple') {
                        const index = mcInstances.findIndex(instance => instance.id === instanceId);
                        if (index !== -1) mcInstances.splice(index, 1);
                    } else if (type === 'true_or_false') {
                        const index = tfInstances.findIndex(instance => instance.id === instanceId);
                        if (index !== -1) tfInstances.splice(index, 1);
                    } else if (type === 'identification') {
                        const index = idInstances.findIndex(instance => instance.id === instanceId);
                        if (index !== -1) idInstances.splice(index, 1);
                    }
                });
                
                return holder;
            }

            selectMultipleChoice.addEventListener('click', function() {
                // Check if we already have 3 instances of this type
                if (mcInstances.length >= 3) {
                    errorMessage.textContent = 'You can only add up to 3 Multiple Choice question sets (one for each difficulty level).';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                // Check if we have used all available difficulty levels
                const usedDifficulties = mcInstances.map(instance => {
                    const difficultySelect = document.getElementById(`difficulty_multiple_${instance.id}`);
                    return difficultySelect ? difficultySelect.value : null;
                }).filter(Boolean);
                
                if (usedDifficulties.length >= 3) {
                    errorMessage.textContent = 'You have already used all difficulty levels for Multiple Choice questions.';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                const newInstance = {
                    id: mcInstanceCounter,
                    type: 'multiple'
                };
                mcInstances.push(newInstance);
                createQuizTypeInstance('multiple', mcInstanceCounter);
                mcInstanceCounter++;
                
                selectQuizTypeModal.classList.add('hidden');
            });

            selectTrueOrFalse.addEventListener('click', function() {
                // Check if we already have 3 instances of this type
                if (tfInstances.length >= 3) {
                    errorMessage.textContent = 'You can only add up to 3 True or False question sets (one for each difficulty level).';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                // Check if we have used all available difficulty levels
                const usedDifficulties = tfInstances.map(instance => {
                    const difficultySelect = document.getElementById(`difficulty_true_or_false_${instance.id}`);
                    return difficultySelect ? difficultySelect.value : null;
                }).filter(Boolean);
                
                if (usedDifficulties.length >= 3) {
                    errorMessage.textContent = 'You have already used all difficulty levels for True or False questions.';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                const newInstance = {
                    id: tfInstanceCounter,
                    type: 'true_or_false'
                };
                tfInstances.push(newInstance);
                createQuizTypeInstance('true_or_false', tfInstanceCounter);
                tfInstanceCounter++;
                
                selectQuizTypeModal.classList.add('hidden');
            });

            selectIdentification.addEventListener('click', function() {
                // Check if we already have 3 instances of this type
                if (idInstances.length >= 3) {
                    errorMessage.textContent = 'You can only add up to 3 Identification question sets (one for each difficulty level).';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                // Check if we have used all available difficulty levels
                const usedDifficulties = idInstances.map(instance => {
                    const difficultySelect = document.getElementById(`difficulty_identification_${instance.id}`);
                    return difficultySelect ? difficultySelect.value : null;
                }).filter(Boolean);
                
                if (usedDifficulties.length >= 3) {
                    errorMessage.textContent = 'You have already used all difficulty levels for Identification questions.';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                const newInstance = {
                    id: idInstanceCounter,
                    type: 'identification'
                };
                idInstances.push(newInstance);
                createQuizTypeInstance('identification', idInstanceCounter);
                idInstanceCounter++;
                
                selectQuizTypeModal.classList.add('hidden');
            });

            // Remove the old event listeners and UI handling for single instances
            // as we're replacing them with the multiple instance support above

            
        //remove the error button
        
        closeErrorModalButton.addEventListener('click', function() {
            errorModal.classList.add('hidden');
        });

        saveQuizButton.addEventListener('click', function() {
            const QuizName = newQuizName.value.trim();
            const QuizType = quiztype.value;
            const QuizNumber = quiznumber.value;
            const difficultyLevel = difficulty.value;

            if (!QuizName) {
                errorMessage.textContent = 'Please enter a name for the quiz.';
                errorModal.classList.remove('hidden');
                return;
            }

            // Prepare submission data
            let submissionData = {
                name: QuizName,
                type: QuizType,
                difficulty: difficultyLevel,
                number: QuizNumber,
                multiple: 0,
                true_or_false: 0,
                identification: 0
            };

            if (QuizType === 'Mixed') {
                // Count total questions for each type from all instances
                let totalMultipleChoice = 0;
                let totalTrueOrFalse = 0;
                let totalIdentification = 0;

                // Prepare arrays to hold details of each instance
                let multipleChoiceInstances = [];
                let trueOrFalseInstances = [];
                let identificationInstances = [];

                // Process Multiple Choice instances
                mcInstances.forEach(instance => {
                    const countSelect = document.getElementById(`multiple_${instance.id}`);
                    const difficultySelect = document.getElementById(`difficulty_multiple_${instance.id}`);
                    
                    if (countSelect && difficultySelect) {
                        const count = parseInt(countSelect.value);
                        const difficulty = difficultySelect.value;
                        
                        totalMultipleChoice += count;
                        
                        multipleChoiceInstances.push({
                            count: count,
                            difficulty: difficulty
                        });
                    }
                });

                // Process True/False instances
                tfInstances.forEach(instance => {
                    const countSelect = document.getElementById(`true_or_false_${instance.id}`);
                    const difficultySelect = document.getElementById(`difficulty_true_or_false_${instance.id}`);
                    
                    if (countSelect && difficultySelect) {
                        const count = parseInt(countSelect.value);
                        const difficulty = difficultySelect.value;
                        
                        totalTrueOrFalse += count;
                        
                        trueOrFalseInstances.push({
                            count: count,
                            difficulty: difficulty
                        });
                    }
                });

                // Process Identification instances
                idInstances.forEach(instance => {
                    const countSelect = document.getElementById(`identification_${instance.id}`);
                    const difficultySelect = document.getElementById(`difficulty_identification_${instance.id}`);
                    
                    if (countSelect && difficultySelect) {
                        const count = parseInt(countSelect.value);
                        const difficulty = difficultySelect.value;
                        
                        totalIdentification += count;
                        
                        identificationInstances.push({
                            count: count,
                            difficulty: difficulty
                        });
                    }
                });

                // Add count totals and instance details to submission data
                submissionData.multiple = totalMultipleChoice;
                submissionData.true_or_false = totalTrueOrFalse;
                submissionData.identification = totalIdentification;
                submissionData.multipleChoiceInstances = multipleChoiceInstances;
                submissionData.trueOrFalseInstances = trueOrFalseInstances;
                submissionData.identificationInstances = identificationInstances;

                // Check if we have at least two quiz instances in total
                let totalInstances = mcInstances.length + tfInstances.length + idInstances.length;
                
                if (totalInstances < 2) {
                    errorMessage.textContent = 'Please add at least two quiz instances for a mixed quiz.';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                // Count how many different quiz types have been selected
                let quizTypesSelected = 0;
                if (mcInstances.length > 0) quizTypesSelected++;
                if (tfInstances.length > 0) quizTypesSelected++;
                if (idInstances.length > 0) quizTypesSelected++;
                
                // Check for duplicate difficulty levels within each quiz type
                let hasDuplicateDifficulties = false;
                
                // Check Multiple Choice instances
                if (mcInstances.length > 1) {
                    const mcDifficulties = mcInstances.map(instance => {
                        const select = document.getElementById(`difficulty_multiple_${instance.id}`);
                        return select ? select.value : null;
                    }).filter(Boolean);
                    
                    if (new Set(mcDifficulties).size !== mcDifficulties.length) {
                        hasDuplicateDifficulties = true;
                    }
                }
                
                // Check True/False instances
                if (!hasDuplicateDifficulties && tfInstances.length > 1) {
                    const tfDifficulties = tfInstances.map(instance => {
                        const select = document.getElementById(`difficulty_true_or_false_${instance.id}`);
                        return select ? select.value : null;
                    }).filter(Boolean);
                    
                    if (new Set(tfDifficulties).size !== tfDifficulties.length) {
                        hasDuplicateDifficulties = true;
                    }
                }
                
                // Check Identification instances
                if (!hasDuplicateDifficulties && idInstances.length > 1) {
                    const idDifficulties = idInstances.map(instance => {
                        const select = document.getElementById(`difficulty_identification_${instance.id}`);
                        return select ? select.value : null;
                    }).filter(Boolean);
                    
                    if (new Set(idDifficulties).size !== idDifficulties.length) {
                        hasDuplicateDifficulties = true;
                    }
                }
                
                // Show error if duplicate difficulties are found
                if (hasDuplicateDifficulties) {
                    errorMessage.textContent = 'You cannot use the same difficulty level twice for the same quiz type.';
                    errorModal.classList.remove('hidden');
                    return;
                }
                
                // For a single quiz type, check if there are different difficulties
                if (quizTypesSelected === 1) {
                    let difficulties = new Set();
                    
                    // Check whichever quiz type has instances
                    if (mcInstances.length > 0) {
                        mcInstances.forEach(instance => {
                            const difficultySelect = document.getElementById(`difficulty_multiple_${instance.id}`);
                            if (difficultySelect) {
                                difficulties.add(difficultySelect.value);
                            }
                        });
                    } else if (tfInstances.length > 0) {
                        tfInstances.forEach(instance => {
                            const difficultySelect = document.getElementById(`difficulty_true_or_false_${instance.id}`);
                            if (difficultySelect) {
                                difficulties.add(difficultySelect.value);
                            }
                        });
                    } else if (idInstances.length > 0) {
                        idInstances.forEach(instance => {
                            const difficultySelect = document.getElementById(`difficulty_identification_${instance.id}`);
                            if (difficultySelect) {
                                difficulties.add(difficultySelect.value);
                            }
                        });
                    }
                    
                    // If there's only one difficulty level across all instances
                    if (difficulties.size < 2) {
                        errorMessage.textContent = 'Please use at least two different difficulty levels for your quiz instances.';
                        errorModal.classList.remove('hidden');
                        return;
                    }
                }
            }

            // Check if the user exceeded the reviewer generation limit
            fetch('/subscription/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.quizLimitReached) {
                        showModal('Error', 'Please upgrade your subscription to add more reviewers.', 'text-red-500', 'OK');
                        modalButton.classList.add('hidden');
                    } else if (data.notSubscribed) {
                        showModal('Error', 'You are not subscribed to any promo yet.', 'text-red-500', 'OK');
                        modalButton.classList.add('hidden');
                    } else {
                        // Show the loader
                        loader.classList.remove('hidden');

                        // Generate the quiz
                        fetch(`/generate-quiz/${topicId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(submissionData),
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Hide the loader
                            if (data.success) {
                                loader.classList.add('hidden');
                                addQuizModal.classList.add('hidden');
                                // Show the success modal
                                successModal.classList.remove('hidden');

                                // Refresh quiz list
                                fetch(`/getquizzes/${topicId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        quizContainer.innerHTML = "";
                                        data.questions.forEach(quiz => {
                                            const button = document.createElement('button');
                                            button.classList.add('question_button','gap-1','w-full','bg-white', 'text-start', 'text-xs', 'sm:text-sm', 'py-2', 'px-3', 'my-2', 'shadow-md', 'rounded-md', 'flex', 'justify-between', 'items-center', 'hover:bg-blue-50', 'delay-75',  'hover:shadow-lg', 'transition', 'duration-300');
                                            button.id = quiz.question_id;
                                            button.innerHTML = `
                                                <p class="w-2/5 ">${quiz.question_title}</p>
                                                <div class="flex justify-between w-3/5">
                                                    <p class="text-xs sm:text-sm items-center">${quiz.question_type}</p>
                                                    <div class="flex justify-between w-1/2  gap-1">
                                                        <p class="w-2/5 flex item-center text-green-500 items-center"> ${quiz.score}/${quiz.number_of_question}</p>
                                                        <div class="flex gap-1 items-center w- 3/5">
                                                            <img class="edit-button w-full h-full max-h-5 object-contain transition-transform duration-300 hover:scale-125" src="/logo_icons/edit.png" alt="edit" data-question-id="${quiz.question_id}">
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
                                        
                                        // Remove all dynamically created instance elements
                                        mcInstances.forEach(instance => {
                                            const element = document.getElementById(`multiple_holder_${instance.id}`);
                                            if (element) element.remove();
                                        });
                                        
                                        tfInstances.forEach(instance => {
                                            const element = document.getElementById(`true_or_false_holder_${instance.id}`);
                                            if (element) element.remove();
                                        });
                                        
                                        idInstances.forEach(instance => {
                                            const element = document.getElementById(`identification_holder_${instance.id}`);
                                            if (element) element.remove();
                                        });
                                        
                                        // Reset the instance arrays
                                        mcInstances.length = 0;
                                        tfInstances.length = 0;
                                        idInstances.length = 0;
                                        
                                        // Reset instance counters
                                        mcInstanceCounter = 0;
                                        tfInstanceCounter = 0;
                                        idInstanceCounter = 0;
                                        
                                        // Show general difficulty selector again
                                        document.getElementById('difficulty').parentElement.classList.remove('hidden');
                                        
                                        // Hide add quiz type button
                                        addQuizTypeButton.classList.add('hidden');
                                        
                                    } else {
                                        alert('Failed to get quizzes: ' + data.message);
                                        loader.classList.add('hidden');
                                    }
                                });
                            } else {
                                alert('Failed to create quiz: ' + data.message);
                                loader.classList.add('hidden');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                } else {
                    console.log(data);
                    loader.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error checking subscription:', error));
        });




        const opened_quizz_holder = document.getElementById('opened_quizz_holder');
        const quiz_menu_holder = document.getElementById('quiz_menu_holder');
        const deleteTopicConfirmModal = document.getElementById('deleteTopicConfirmModal');
        const confirmTopicDelete = document.getElementById('confirmTopicDelete');

            // Event delegation for delete buttons
        quizContainer.addEventListener('click', function(event) {
            const deleteButton = event.target.closest('.delete-button');
            const edit = event.target.closest('.edit-button');                          
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
                
            }else if(edit){

                const editQuizModal = document.getElementById('editQuizModal');
                const editQuizName = document.getElementById('editQuizName');
                const saveEditQuizButton = document.getElementById('saveEditQuizButton');
                const cancelEditQuizButton = document.getElementById('cancelEditQuizButton');
                let currentQuizId = null;
                
                event.stopPropagation(); // Prevent the event from propagating to the parent elements
                event.preventDefault(); // Prevent the default action
                currentQuizId = edit.dataset.questionId;
                const currentQuizName = edit.closest('.question_button').querySelector('p').textContent;
                editQuizName.value = currentQuizName;
                editQuizModal.classList.remove('hidden');
               

                
                const closeSuccessModalButton = document.getElementById('closeSuccessModalButton');
                closeSuccessModalButton.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                });

                saveEditQuizButton.addEventListener('click', function() {
                    const newQuizName = editQuizName.value.trim();
                    if (!newQuizName) {
                        alert('Please enter a new name for the quiz.');
                        return;
                    }

                    fetch(`/editquiz/${currentQuizId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name: newQuizName })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const quizButton = document.getElementById(currentQuizId);
                            quizButton.querySelector('p').textContent = newQuizName;
                            editQuizModal.classList.add('hidden');
                        } else {
                            alert('Failed to edit quiz: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });

                cancelEditQuizButton.addEventListener('click', function() {
                    editQuizModal.classList.add('hidden');
                });

            }else if(button) {
                // Event delegation for question buttons
                console.log("question is clicked");
                const questionId = button.id;
                window.location.href = `/quizresult?questionId=${questionId}`;
            }



        
        });
});

</script>

</x-layout>