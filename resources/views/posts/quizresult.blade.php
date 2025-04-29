<x-layout>

    <div class="max-w-2xl h-full mx-auto ] rounded-lg">
        
        <div class="w-full max-w-2xl ">

            <div id="opened_quizz_holder" class="bg-white rounded-xl h-full w-full px-6 py-3 my-3">
                <!-- Quiz info will be dynamically inserted here -->
            </div>

            <div id="quiz_result" class="quiz-container text-sm mt-2 rounded-lg px-5 pt-5 pb-3 bg-white">
                <!-- Quiz result will be dynamically inserted here -->
            </div>
            
            <!-- Reset Quiz Button -->
            <div id="reset_quiz_container" class="flex justify-center mt-4 mb-6">
                <button id="resetQuizButton" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Reset Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Reset Quiz Confirmation Modal -->
    <div id="resetQuizConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md max-w-md">
            <h2 class="text-xl font-bold mb-4">Reset Quiz</h2>
            <p>Are you sure you want to reset this quiz? All answers and your score will be cleared.</p>
            <div class="flex justify-end mt-4">
                <button id="cancelReset" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">Cancel</button>
                <button id="confirmReset" class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded">Reset</button>
            </div>
        </div>
    </div>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            const bottom_nav = document.getElementById('bottom_nav');
            bottom_nav.classList.add('hidden');

            //this is the function to clear the quiz related from locat storage
            function clearQuizData() {
                // Clear all quiz-related data from localStorage
                Object.keys(localStorage).forEach(key => {
                    if (key.startsWith('question_') || key.startsWith('unanswered_')) {
                        localStorage.removeItem(key);
                    }
                });
            }
            clearQuizData(); // Clear the quiz data

            // Format timer_result into a more readable format (1m 3s)
            function formatTime(timeString) {
                if (!timeString) return "0s";
                
                // Parse the HH:MM:SS format
                const parts = timeString.split(':');
                const hours = parseInt(parts[0]);
                const minutes = parseInt(parts[1]);
                const seconds = parseInt(parts[2]);
                
                let result = '';
                if (hours > 0) result += `${hours}h `;
                if (minutes > 0 || hours > 0) result += `${minutes}m `;
                result += `${seconds}s`;
                
                return result.trim();
            }
            
            // this will go to the reviewer page
            const urlParams = new URLSearchParams(window.location.search);
            const questionId = urlParams.get('questionId');
            const opened_quizz_holder = document.getElementById('opened_quizz_holder');
            const resetQuizButton = document.getElementById('resetQuizButton');
            const resetQuizConfirmModal = document.getElementById('resetQuizConfirmModal');
            const cancelReset = document.getElementById('cancelReset');
            const confirmReset = document.getElementById('confirmReset');
            let topicId = null;

            // Reset quiz functionality
            resetQuizButton.addEventListener('click', function() {
                resetQuizConfirmModal.classList.remove('hidden');
            });

            cancelReset.addEventListener('click', function() {
                resetQuizConfirmModal.classList.add('hidden');
            });

            confirmReset.addEventListener('click', function() {
                resetQuizConfirmModal.classList.add('hidden');
                
                // Call API to reset the quiz
                fetch(`/resetquiz/${questionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show the reset quiz
                        window.location.reload();
                    } else {
                        alert('Failed to reset quiz: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error resetting quiz:', error);
                });
            });

            fetch(`getquiz/${questionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        topicId = data.question.topic_id;
                        if(opened_quizz_holder){ 
                            opened_quizz_holder.innerHTML = ''; // Clear previous content
                        }

                        // Create the quiz info div
                        const quizInfoDiv = document.createElement('div');
                        quizInfoDiv.id = 'quiz_info';
                        quizInfoDiv.classList.add('w-full', 'max-w-2xl');
                        quizInfoDiv.innerHTML = `
                            <div class="bg-white rounded-lg shadow-sm">
                                <!-- Header with back button -->
                                <div class="border-b border-gray-100 px-4 py-2">
                                    <h1 id="backbutton" class="cursor-pointer text-blue-600 text-sm font-medium flex items-center">
                                        <span class="mr-1">&#129120;</span> Back to Quizzes
                                    </h1>
                                </div>
                                
                                <!-- Quiz title and info -->
                                <div class="px-4 py-3">
                                    <div class="mb-2">
                                        <h2 class="text-lg font-bold text-gray-800">${data.question.question_title}</h2>
                                        <p class="text-xs text-gray-500">${data.question.question_type} Quiz</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Questions:</span>
                                            <span class="font-medium">${data.question.number_of_question}</span>
                                        </div>
                                        
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">High Score:</span>
                                            <span class="font-medium text-green-600">${data.question.high_score} / ${data.question.number_of_question}</span>
                                        </div>
                                        
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Recent Score:</span>
                                            <span class="font-medium text-blue-600">${data.question.score} / ${data.question.number_of_question}</span>
                                        </div>
                                        
                                        <div class="flex flex-col">
                                            <span class="text-gray-500">Time:</span>
                                            <span class="font-medium text-orange-500">${formatTime(data.question.timer_result)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        opened_quizz_holder.appendChild(quizInfoDiv);

                        // Create the start quiz button
                        const startQuizButton = document.createElement('button');
                        startQuizButton.id = data.question.question_id;
                        startQuizButton.classList.add('startQuiz', 'bg-blue-500', 'w-full', 'text-white', 'font-medium', 'py-2', 'rounded', 'mt-2', 'hover:bg-blue-600', 'transition');
                        startQuizButton.textContent = 'Start Quiz';
                        opened_quizz_holder.appendChild(startQuizButton);
                    } else {
                        alert('Failed to get quizzes: ' + data.message);
                    }
                });
                
                // Event delegation for back button
                opened_quizz_holder.addEventListener('click', function(event) {

                    //for start quiz button
                    const button2 = event.target.closest('.startQuiz');
                    if (button2) {
                        console.log("start quiz button is clicked");
                        fetch(`startquiz/${button2.id}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Redirect to the next page
                                    console.log("quiz started");
                                    console.log(data)
                                    window.location.href = `/takequiz/${button2.id}`;
                                
                                } else {
                                    alert('Failed to start quiz: ' + data.message);
                                }
                            });
                    }
                    //for back button
                    const button = event.target.closest('#backbutton');
                    if (button) {
                        window.location.href=`/quiz?topicId=${topicId}`;
                    }
                });

                const quiz_result = document.getElementById('quiz_result');
                const reset_quiz_container = document.getElementById('reset_quiz_container');

                //this is for the summary of the getQuizResult
                fetch(`getquizresult/${questionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);
                        
                        // Show reset button only when there are answers
                        reset_quiz_container.classList.remove('hidden');

                        if(quiz_result){ 
                            quiz_result.innerHTML = ''; // Clear previous content

                            // Add quiz summary
                            const quizSummary = document.createElement('div');
                            quizSummary.className = 'mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200';
                            quizSummary.innerHTML = `
                                <h2 class="text-lg font-semibold mb-2">Quiz Results</h2>
                                <div class="flex justify-between">
                                    <div class="text-center px-4 py-2 bg-green-100 rounded-lg">
                                        <p class="font-semibold text-green-700">Correct Answers</p>
                                        <p class="text-xl font-bold text-green-700">${data.type === 'Mixed' ? 
                                            (data.questions.multiple_choice ? data.questions.multiple_choice.filter(q => q.answer === q.user_answer).length : 0) +
                                            (data.questions.true_or_false ? data.questions.true_or_false.filter(q => q.answer === q.user_answer).length : 0) +
                                            (data.questions.identification ? data.questions.identification.filter(q => q.answer.toLowerCase() === (q.user_answer || '').toLowerCase()).length : 0)
                                            : data.questions.filter(q => {
                                                if (data.type === 'Identification') {
                                                    return q.answer.toLowerCase() === (q.user_answer || '').toLowerCase();
                                                } else {
                                                    return q.answer === q.user_answer;
                                                }
                                            }).length
                                        }</p>
                                    </div>
                                    <div class="text-center px-4 py-2 bg-red-100 rounded-lg">
                                        <p class="font-semibold text-red-700">Wrong Answers</p>
                                        <p class="text-xl font-bold text-red-700">${data.type === 'Mixed' ? 
                                            (data.questions.multiple_choice ? data.questions.multiple_choice.filter(q => q.user_answer && q.answer !== q.user_answer).length : 0) +
                                            (data.questions.true_or_false ? data.questions.true_or_false.filter(q => q.user_answer && q.answer !== q.user_answer).length : 0) +
                                            (data.questions.identification ? data.questions.identification.filter(q => q.user_answer && q.answer.toLowerCase() !== q.user_answer.toLowerCase()).length : 0)
                                            : data.questions.filter(q => {
                                                if (!q.user_answer) return false;
                                                if (data.type === 'Identification') {
                                                    return q.answer.toLowerCase() !== q.user_answer.toLowerCase();
                                                } else {
                                                    return q.answer !== q.user_answer;
                                                }
                                            }).length
                                        }</p>
                                    </div>
                                </div>
                            `;
                            quiz_result.appendChild(quizSummary);

                            if (data.type === 'Mixed') {
                                // Handle mixed quiz type
                                let questionCounter = 1;
                                const mixedQuestions = data.questions;
                                
                                ['multiple_choice', 'true_or_false', 'identification'].forEach(type => {
                                    if (mixedQuestions[type]) {
                                        mixedQuestions[type].forEach((question, index) => {
                                            const questionDiv = document.createElement('div');
                                            questionDiv.classList.add('question', 'mb-6', 'pb-2', 'border-b', 'border-gray-200');
                                            
                                            if (type === 'multiple_choice') {
                                                questionDiv.innerHTML = `
                                                    <p class="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}</p>
                                                    <p class="text-green-500">Correct Answer : ${question.answer}</p>
                                                    <ul>
                                                        <li><label class="choices${index}A w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="A"> A) ${question.A}</label></li>
                                                        <li><label class="choices${index}B w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="B"> B) ${question.B}</label></li>
                                                        <li><label class="choices${index}C w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="C"> C) ${question.C}</label></li>
                                                        <li><label class="choices${index}D w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="D"> D) ${question.D}</label></li>
                                                    </ul>
                                                `;
                                            } else if (type === 'true_or_false') {
                                                questionDiv.innerHTML = `
                                                    <p class="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}</p>
                                                    <p class="text-green-500">Correct Answer : ${question.answer}</p>
                                                    <ul>
                                                        <li><label class="choicesTF${index}True w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="True">True </label></li>
                                                        <li><label class="choicesTF${index}False w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="False"> False </label></li>
                                                    </ul>
                                                `;
                                            } else if (type === 'identification') {
                                                questionDiv.innerHTML = `
                                                    <p class="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}</p>
                                                    <p class="text-green-500">Correct Answer : ${question.answer} </p>
                                                    <ul>
                                                        <li><label class="choicesID${index} w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input class="w-full px-1" type="text" disabled name="question_${index}" value="${question.user_answer || ''}"> </label></li>
                                                    </ul>
                                                `;
                                            }
                                            questionCounter++;
                                            quiz_result.appendChild(questionDiv);

                                            // Highlight the correct answer and wrong answer
                                            const correctAnswer = question.answer;
                                            const userAnswer = question.user_answer;
                                            const correctAnswerLabel = questionDiv.querySelectorAll(`input[name="question_${index}"]`);

                                            if (correctAnswerLabel.length === 0) {
                                                console.log('No correct answer found', index);
                                            } else {
                                                correctAnswerLabel.forEach(label => {
                                                    if (type === 'identification') {
                                                        const correctanswer = correctAnswer.toLowerCase();
                                                        const useranswer = userAnswer ? userAnswer.toLowerCase() : '';
                                                        const choices = questionDiv.querySelector(`.choicesID${index}`);
                                                        if (correctanswer === useranswer) {
                                                            choices.classList.remove('bg-blue-50');
                                                            choices.classList.add('bg-green-200');
                                                        } else {
                                                            choices.classList.remove('bg-blue-50');
                                                            choices.classList.add('bg-red-200');
                                                        }
                                                    } else if (type === 'true_or_false') {
                                                        if (label.value === userAnswer) {
                                                            const tfLabel = label.value === 'True' ? 
                                                                questionDiv.querySelector(`.choicesTF${index}True`) : 
                                                                questionDiv.querySelector(`.choicesTF${index}False`);
                                                            
                                                            if (tfLabel) {
                                                                tfLabel.classList.remove('bg-blue-50');
                                                                if (userAnswer === correctAnswer) {
                                                                    tfLabel.classList.add('bg-green-200');
                                                                } else {
                                                                    tfLabel.classList.add('bg-red-200');
                                                                }
                                                                label.checked = true;
                                                            }
                                                        }
                                                        
                                                        // Also highlight the correct answer if user got it wrong
                                                        if (userAnswer !== correctAnswer) {
                                                            const correctTfLabel = correctAnswer === 'True' ?
                                                                questionDiv.querySelector(`.choicesTF${index}True`) :
                                                                questionDiv.querySelector(`.choicesTF${index}False`);
                                                                
                                                            if (correctTfLabel) {
                                                                correctTfLabel.classList.remove('bg-blue-50');
                                                                correctTfLabel.classList.add('bg-green-100');
                                                                correctTfLabel.classList.add('border');
                                                                correctTfLabel.classList.add('border-green-500');
                                                            }
                                                        }
                                                    } else { // multiple_choice
                                                        if (label.value === userAnswer) {
                                                            let letter = label.value;
                                                            const choice = questionDiv.querySelector(`.choices${index}${letter}`);
                                                            if (choice) {
                                                                choice.classList.remove('bg-blue-50');
                                                                choice.classList.add(correctAnswer === userAnswer ? 'bg-green-200' : 'bg-red-200');
                                                                label.checked = true;
                                                            }
                                                        }
                                                        
                                                        // Highlight the correct answer if user selected wrong
                                                        if (userAnswer && userAnswer !== correctAnswer) {
                                                            const correctChoice = questionDiv.querySelector(`.choices${index}${correctAnswer}`);
                                                            if (correctChoice) {
                                                                correctChoice.classList.add('border');
                                                                correctChoice.classList.add('border-green-500');
                                                                correctChoice.classList.add('bg-green-100');
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            } else {
                                // Handle single quiz type
                                data.questions.forEach((question, index) => {
                                    const questionDiv = document.createElement('div');
                                    questionDiv.classList.add('question', 'mb-6', 'pb-2', 'border-b', 'border-gray-200');
                                
                                    if(data.type === 'Multiple Choice'){
                                        console.log('multiple');
                                        questionDiv.innerHTML = `
                                            <p class="text-gray-700 font-semibold">${index + 1}) ${question.question_text}</p>
                                            <p class="text-green-500">Correct Answer : ${question.answer}</p>
                                            <ul>
                                                <li><label class="choices${index}A w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="A"> A) ${question.A}</label></li>
                                                <li><label class="choices${index}B w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="B"> B) ${question.B}</label></li>
                                                <li><label class="choices${index}C w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="C"> C) ${question.C}</label></li>
                                                <li><label class="choices${index}D w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="D"> D) ${question.D}</label></li>
                                            </ul>
                                        `;
                                    }
                                    else if(data.type === 'True or false'){
                                        console.log('TF?');
                                        questionDiv.innerHTML = `
                                            <p class="text-gray-700 font-semibold">${index + 1}) ${question.question_text}</p>
                                            <p class="text-green-500">Correct Answer : ${question.answer}</p>
                                            <ul>
                                                <li><label class="choicesTF${index}True w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="True">True </label></li>
                                                <li><label class="choicesTF${index}False w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input type="radio" disabled name="question_${index}" value="False"> False </label></li>
                                            </ul>
                                        `;
                                    }
                                    else if(data.type === 'Identification'){
                                        const lower = question.user_answer;
                                        console.log('ID?');
                                        questionDiv.innerHTML = `
                                            <p class="text-gray-700 font-semibold">${index + 1}) ${question.question_text}</p>
                                            <p class="text-green-500">Correct Answer : ${question.answer} </p>
                                            <ul>
                                                <li><label class="choicesID${index} w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center"><input class="w-full px-1" type="text" disabled name="question_${index}" value="${question.user_answer || ''}"> </label></li>
                                            </ul>
                                        `;
                                    }

                                    quiz_result.appendChild(questionDiv);

                                    // Highlight the correct answer
                                    const correctAnswer = question.answer;
                                    const userAnswer = question.user_answer;
                                    const correctAnswerLabel = questionDiv.querySelectorAll(`input[name="question_${index}"]`);

                                    if(correctAnswerLabel.length === 0){
                                        console.log('No correct answer found', index);
                                    } else {
                                        correctAnswerLabel.forEach(label => {
                                            if(data.type === 'Identification'){
                                                const correctanswer = correctAnswer.toLowerCase();
                                                const useranswer = userAnswer ? userAnswer.toLowerCase() : '';
                                                const choices = questionDiv.querySelector(`.choices`);
                                                if(correctanswer === useranswer){
                                                    choices.classList.remove('bg-blue-50');
                                                    choices.classList.remove('bg-red-200');
                                                    choices.classList.add('bg-green-200');
                                                } else {
                                                    choices.classList.remove('bg-blue-50');
                                                    choices.classList.remove('bg-green-200');
                                                    choices.classList.add('bg-red-200');
                                                }
                                            } else {
                                                if (label.value === userAnswer) {
                                                    let letter = label.value;
                                                    const choices = questionDiv.querySelectorAll(`.choices${index}${letter}`);
                                                    choices.forEach(choice => {
                                                        if(choice.classList.contains('choices'+index+userAnswer)){
                                                            choice.classList.remove('bg-blue-50');
                                                            choice.classList.add('bg-red-200');
                                                            if(correctAnswer == userAnswer){
                                                                choice.classList.remove('bg-red-200');
                                                                choice.classList.add('bg-green-200');
                                                            }
                                                            label.checked = true;
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    } else {
                        console.log("the user did not take the quiz yet")
                        quiz_result.innerHTML = `
                            <div class="text-center py-8">
                                <p class="text-red-500 font-semibold mb-2">You have not taken the quiz yet!</p>
                                <p class="text-gray-600 text-sm">Click the "Start Quiz" button above to begin.</p>
                            </div>`;
                        
                        // Hide reset button when no answers
                        reset_quiz_container.classList.add('hidden');
                    }
                });
         });    
    </script>
</x-layout>