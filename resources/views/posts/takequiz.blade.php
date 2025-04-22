<x-layout>

    <div class=" z-50 sticky top-12 bg-white px-6 py-3 w-full shadow-lg flex items-center justify-center">
        <p id="title" class=" text-blue-500 w-full max-w-2xl items-center rounded-e-lg rounded-b-lg  py-1"></p>
        <div class="timer-container bg-orange-50 border-l border-orange-200 px-3 py-1 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-orange-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-orange-500 text-sm">Timer: <span id="timer" class="font-semibold">00:00</span></p>
        </div>
    </div>
    <div class="max-w-2xl h-full mx-auto bg-white rounded-lg">
        
        <div class="w-full max-w-2xl">

                <div class="quiz-container bg-white text-sm px-5 pt-5 pb-3 ">
                    <form id="quizForm" method="POST">
                        @csrf
                        <!-- Questions will be dynamically inserted here -->
                    </form>
                    <button id="submitQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Quiz</button>
                </div>
                

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer variables
            let seconds = 0;
            let timerInterval;
            const timerDisplay = document.getElementById('timer');
            
            // Generate a unique session ID for this page visit
            const pageSessionId = Date.now().toString();
            
            // Start the timer
            function startTimer(quizId) {
                // Get the last page action (refresh or navigate)
                const lastAction = sessionStorage.getItem('lastQuizPageAction');
                const lastQuizId = localStorage.getItem('currentQuizId');
                const quizStartTime = localStorage.getItem('quizStartTime');
                const lastPageSession = sessionStorage.getItem('quizPageSession');
                
                // Clear the page action marker
                sessionStorage.removeItem('lastQuizPageAction');
                
                // Set current session ID
                sessionStorage.setItem('quizPageSession', pageSessionId);
                
                // Check if this is a refresh (same session) or navigation (new session)
                const isRefresh = lastPageSession && lastAction === 'refresh';
                const isNewQuiz = lastQuizId !== quizId.toString();
                
                // Reset timer if back button was used or if it's a different quiz
                if ((!isRefresh || isNewQuiz) && lastAction !== 'refresh') {
                    // This is a new session or new quiz, reset the timer
                    localStorage.setItem('quizStartTime', Date.now().toString());
                    localStorage.setItem('currentQuizId', quizId.toString());
                    seconds = 0;
                } else if (quizStartTime && lastQuizId === quizId.toString()) {
                    // Continue from where we left off (refresh case)
                    const startTime = parseInt(quizStartTime);
                    const currentTime = Date.now();
                    seconds = Math.floor((currentTime - startTime) / 1000);
                } else {
                    // Fallback - start a new timer
                    localStorage.setItem('quizStartTime', Date.now().toString());
                    localStorage.setItem('currentQuizId', quizId.toString());
                    seconds = 0;
                }
                
                // Update timer display immediately
                updateTimerDisplay();
                
                // Start the interval to update the timer
                timerInterval = setInterval(function() {
                    seconds++;
                    updateTimerDisplay();
                }, 1000);
            }
            
            // Update timer display with pulse animation when seconds change
            function updateTimerDisplay() {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                
                // Use the new format (1m 3s) for the timer display
                if (minutes > 0) {
                    timerDisplay.textContent = `${minutes}m ${remainingSeconds}s`;
                } else {
                    timerDisplay.textContent = `${remainingSeconds}s`;
                }
            }
            
            // Before page unload, mark the action type
            window.addEventListener('beforeunload', function(event) {
                // Mark this as a potential refresh
                sessionStorage.setItem('lastQuizPageAction', 'refresh');
            });

            // The $questions variable in the Blade template comes from the takeQuiz method in the QuizController
            const questions = @json($questions);
            let questionid = null;
            const quizForm = document.getElementById('quizForm');
            let quiztype = null;
            let topicId = null;
            
            // Determine the question ID based on the quiz type
            if (Array.isArray(questions)) {
                questionid = questions[0].question_id;
            } else if (questions.multiple_choice && questions.multiple_choice.length > 0) {
                questionid = questions.multiple_choice[0].question_id;
            } else if (questions.true_or_false && questions.true_or_false.length > 0) {
                questionid = questions.true_or_false[0].question_id;
            } else if (questions.identification && questions.identification.length > 0) {
                questionid = questions.identification[0].question_id;
            }

            // Start the timer with the quiz ID
            startTimer(questionid);

            // Get the title of the question
            fetch(`/getquiz/${questionid}`)
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    const title = document.getElementById('title');
                    title.classList.add('cursor-pointer','font-medium');
                    title.innerHTML = `<span>&#129120</span> ${data.question.question_type} quiz for  ${data.question.question_title}`;
                    topicId = data.question.question_id;
                    quiztype = data.question.question_type;

                    let questionCounter = 1;

                    if(quiztype === 'Multiple Choice'){
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question','py-2');
                            questionDiv.innerHTML = `
                                <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="A"> A) ${question.A}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="B"> B) ${question.B}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="C"> C) ${question.C}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="D"> D) ${question.D}</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);

                            // Retrieve saved answers from localStorage
                            const savedAnswer = localStorage.getItem(`question_${index}`);
                            if (savedAnswer) {
                                const input = questionDiv.querySelector(`input[value="${savedAnswer}"]`);
                                if (input) {
                                    input.checked = true;
                                    const selectedLabel = input.closest('label');
                                    const labels = questionDiv.querySelectorAll('label');
                                    
                                    labels.forEach(label => {
                                        label.classList.add('shadow-sm');
                                        label.classList.remove('shadow-md');
                                        label.classList.remove('bg-blue-300');
                                        label.classList.add('bg-blue-50');
                                    });
                                    
                                    selectedLabel.classList.remove('bg-blue-50');
                                    selectedLabel.classList.add('bg-blue-300');
                                    selectedLabel.classList.remove('shadow-sm');
                                    selectedLabel.classList.add('shadow-md');
                                }
                            }
                                
                            questionCounter++;
                        });
                    }
                    else if(quiztype === 'True or false'){
                        console.log('TF?');
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="True">  True</label></li>
                                    <li><label class = "true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="False">  False</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);

                            // Retrieve saved answers from localStorage
                            const savedAnswer = localStorage.getItem(`question_${index}`);
                            if (savedAnswer) {
                                const input = questionDiv.querySelector(`input[value="${savedAnswer}"]`);
                                if (input) {
                                    input.checked = true;
                                    const selectedLabel = input.closest('label');
                                    const labels = questionDiv.querySelectorAll('label');
                                    
                                    labels.forEach(label => {
                                        label.classList.add('shadow-sm');
                                        label.classList.remove('shadow-md');
                                        label.classList.remove('bg-blue-300');
                                        label.classList.add('bg-blue-50');
                                    });
                                    
                                    selectedLabel.classList.remove('bg-blue-50');
                                    selectedLabel.classList.add('bg-blue-300');
                                    selectedLabel.classList.remove('shadow-sm');
                                    selectedLabel.classList.add('shadow-md');
                                }
                            }

                            questionCounter++;
                        });
                    }
                    else if(quiztype === 'Identification'){
                        console.log('akwdoadok');
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "identification w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input class = "w-full  px-1" type="text" placeholder="Input Answer" name="question_${index}" > </label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);

                            // Retrieve saved answers from localStorage
                                const savedAnswer = localStorage.getItem(`question_${index}`);
                                if (savedAnswer) {
                                    const input = questionDiv.querySelector('input');
                                    if (input) {
                                        input.value = savedAnswer;
                                    }
                                }

                            questionCounter++;
                        });
                    }
                    else if(quiztype === 'Mixed'){
                    console.log('Mixed');
                    const mixedQuestions = questions;
                    
                    let questionCounter = 1;
                    ['multiple_choice', 'true_or_false', 'identification'].forEach(type => {
                        if (mixedQuestions[type]) {
                            mixedQuestions[type].forEach((question) => {
                                const questionDiv = document.createElement('div');
                                questionDiv.classList.add('question', 'py-2');
                                
                                if (type === 'multiple_choice') {
                                    questionDiv.innerHTML = `
                                        <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_A"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_A" value="A"> A) ${question.A}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_B"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_B" value="B"> B) ${question.B}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_C"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_C" value="C"> C) ${question.C}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_D"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_D" value="D"> D) ${question.D}</label></li>
                                        </ul>
                                    `;
                                } else if (type === 'true_or_false') {
                                    questionDiv.innerHTML = `
                                        <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_True"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_True" value="True">  True</label></li>
                                            <li><label class="true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_False"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_False" value="False">  False</label></li>
                                        </ul>
                                    `;
                                } else if (type === 'identification') {
                                    questionDiv.innerHTML = `
                                        <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="identification w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}"><input class="w-full px-1" type="text" placeholder="Input Answer" name="question_${questionCounter}" id="question_${questionCounter}"></label></li>
                                        </ul>
                                    `;
                                }

                                quizForm.appendChild(questionDiv);

                                // Retrieve saved answers from localStorage
                                const savedAnswer = localStorage.getItem(`question_${questionCounter}`);
                                if (savedAnswer) {
                                    if (type === 'multiple_choice' || type === 'true_or_false') {
                                        const input = questionDiv.querySelector(`input[value="${savedAnswer}"]`);
                                        if (input) {
                                            input.checked = true;
                                            const selectedLabel = input.closest('label');
                                            const labels = questionDiv.querySelectorAll('label');
                                            
                                            labels.forEach(label => {
                                                label.classList.add('shadow-sm');
                                                label.classList.remove('shadow-md');
                                                label.classList.remove('bg-blue-300');
                                                label.classList.add('bg-blue-50');
                                            });
                                            
                                            selectedLabel.classList.remove('bg-blue-50');
                                            selectedLabel.classList.add('bg-blue-300');
                                            selectedLabel.classList.remove('shadow-sm');
                                            selectedLabel.classList.add('shadow-md');
                                        }
                                    } else if (type === 'identification') {
                                        const input = questionDiv.querySelector('input');
                                        if (input) {
                                            input.value = savedAnswer;
                                        }
                                    }
                                }
                                questionCounter++;
                            });
                        }
                    });

                }

                   
                    const submitQuizButton = document.getElementById('submitQuizButton');
                    const quizContainer = document.querySelector('.quiz-container');
                    const questionDivs = quizContainer.querySelectorAll('.question');
                    

                    // show the errror if the brower is refresh
                    questionDivs.forEach((questionDiv, index) => {
                            const errorSpan = questionDiv.querySelector(`#q${index + 1}`);
                            if (localStorage.getItem(`unanswered_${index}`) === 'true') {
                                if (errorSpan) {
                                    errorSpan.textContent = 'Please answer this question';
                                }
                            } else {
                                if (errorSpan) {
                                    errorSpan.textContent = '';
                                }
                            }
                        });

                    questionCounter = 1; // Initialize questionCounter for validation

                    submitQuizButton.addEventListener('click', function(event) {
                        // Prevent the default form submission
                        event.preventDefault();

                        //validation here
                        
                        let allAnswered = true;
                        const questionDivs = quizContainer.querySelectorAll('.question');
                        questionDivs.forEach((questionDiv, index) => {
                            const inputs = questionDiv.querySelectorAll('input');
                            let answered = false;
                            inputs.forEach(input => {
                                if ((input.type === 'radio' && input.checked) || (input.type === 'text' && input.value.trim() !== '')) {
                                    answered = true;
                                }
                            });
                            if (!answered) {
                                allAnswered = false;
                                const errorSpan = questionDiv.querySelector(`#q${index + 1}`);
                                if (errorSpan) {
                                    errorSpan.textContent = 'Please answer this question';
                                }
                            } else {
                                const errorSpan = questionDiv.querySelector(`#q${index + 1}`);
                                if (errorSpan) {
                                    errorSpan.textContent = '';
                                }
                            }
                        });



                        if (!allAnswered) {
                            // Save the unanswered questions state in localStorage
                            questionDivs.forEach((questionDiv, index) => {
                                    const inputs = questionDiv.querySelectorAll('input');
                                    let answered = false;
                                    inputs.forEach(input => {
                                        if ((input.type === 'radio' && input.checked) || (input.type === 'text' && input.value.trim() !== '')) {
                                            answered = true;
                                        }
                                    });
                                    if (!answered) {
                                        localStorage.setItem(`unanswered_${index}`, 'true');
                                    } else {
                                        localStorage.removeItem(`unanswered_${index}`);
                                    }
                                });
                            // alert('Please answer all questions before submitting the quiz.');
                           // Show modal instead of alert
                            const validateModal = document.createElement('div');
                            validateModal.id = 'validateModal';
                            validateModal.classList.add('z-50', 'fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                            validateModal.innerHTML = `
                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                    <h2 class="text-2xl mb-4">Oh No!</h2>
                                    <p id="scoreText" class="text-lg">Please answer all questions before submitting the quiz.</p>
                                    <button id="closeModalButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
                                </div>
                            `;
                            document.body.appendChild(validateModal);

                            const closeModalButton = document.getElementById('closeModalButton');
                            closeModalButton.addEventListener('click', function() {
                                validateModal.remove();
                            });
                            return;
                        }

                        // Clear the timer when the quiz is submitted
                        if (timerInterval) {
                            clearInterval(timerInterval);
                        }
                        
                        // Get the final time result in seconds
                        const timeResult = seconds;
                        
                        // Reset the stored start time when quiz is submitted
                        localStorage.removeItem('quizStartTime');
                        localStorage.removeItem('currentQuizId');
                        sessionStorage.removeItem('quizPageSession');
                        sessionStorage.removeItem('lastQuizPageAction');

                        //if validaten continue
                        const formData = new FormData(quizForm);
                        quiztype = data.question.question_type;
                        console.log(quiztype);
                        const answers = {
                            multiple_choice: [],
                            true_or_false: [],
                            identification: [],
                            questionId: questionid,
                            timeResult: timeResult  // Include timeResult in the initial object
                        };

                        // For mixed quizzes, organize answers by question type
                        const multipleChoiceLabels = document.querySelectorAll('.multiple_choice');
                        const trueOrFalseLabels = document.querySelectorAll('.true_or_false');
                        const identificationLabels = document.querySelectorAll('.identification');
                        
                        // Process multiple choice questions
                        multipleChoiceLabels.forEach((label, index) => {
                            const input = label.querySelector('input');
                            if (input && input.checked) {
                                answers.multiple_choice[Math.floor(index/4)] = input.value;
                            }
                        });
                        
                        // Process true/false questions
                        trueOrFalseLabels.forEach((label, index) => {
                            const input = label.querySelector('input');
                            if (input && input.checked) {
                                answers.true_or_false[Math.floor(index/2)] = input.value;
                            }
                        });
                        
                        // Process identification questions
                        identificationLabels.forEach((label, index) => {
                            const input = label.querySelector('input');
                            if (input) {
                                answers.identification[index] = input.value.trim();
                            }
                        });

                        console.log('Submitting quiz with data:', answers);

                        // Submit with fetch using POST method and CSRF token
                        fetch('/submitquiz', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(answers)
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.error('Server returned status:', response.status);
                                return response.text().then(text => {
                                    throw new Error(`Server error: ${text || response.statusText}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Clear stored quiz data
                                localStorage.removeItem('quizStartTime');
                                
                                // Display results in modal
                                const scoreModal = document.createElement('div');
                                scoreModal.id = 'scoreModal';
                                scoreModal.classList.add('z-50', 'fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                                
                                // Format time for display in the new format (1m 3s)
                                const minutes = Math.floor(timeResult / 60);
                                const seconds = timeResult % 60;
                                const formattedTime = minutes > 0 
                                    ? `${minutes}m ${seconds}s` 
                                    : `${seconds}s`;
                                
                                // Check if this is a new high score
                                const isNewHighScore = data.is_new_high_score;
                                
                                scoreModal.innerHTML = `
                                    <div class="bg-white p-6 rounded-lg shadow-lg">
                                        <h2 class="text-2xl mb-4">Congratulation!!</h2>
                                        <p class="text-lg mb-2">Your score: <span class="font-bold">${data.score}</span></p>
                                        <p class="text-lg mb-3">Time: <span class="font-semibold">${formattedTime}</span></p>
                                        ${isNewHighScore ? '<p class="text-green-500 font-bold mb-3">🎉 New High Score! 🎉</p>' : ''}
                                        <p id="scoreText" class="text-lg">The result of your quiz is ready.</p>
                                        <button id="closeModalButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Result</button>
                                    </div>
                                `;
                                document.body.appendChild(scoreModal);

                                const closeModalButton = document.getElementById('closeModalButton');
                                closeModalButton.addEventListener('click', function() {
                                    scoreModal.remove();
                                    window.location.href=`/quizresult?questionId=${data.question_id}`;
                                });

                            
                            } else {
                                alert('Failed to submit quiz: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting quiz:', error);
                            alert('Error submitting quiz: ' + error.message);
                        });
                    });

                }
            }).catch(error => console.error('Error:', error));

            const title = document.getElementById('title');
            title.addEventListener('click', function() {
                window.location.href=`/quizresult?questionId=${topicId}`;
            });

            const botnav = document.getElementById('bottom_nav');
            botnav.classList.add('hidden');
        
            quizForm.addEventListener('change', function(event) {
                if (event.target.type === 'radio') {
                    const selectedLabel = event.target.closest('label');
                    const questionGroup = selectedLabel.closest('.question');
                    const labels = questionGroup.querySelectorAll('label');
                    
                    labels.forEach(label => {
                        label.classList.add('shadow-sm');
                        label.classList.remove('shadow-md');
                        label.classList.remove('bg-blue-300');
                        label.classList.add('bg-blue-50');
                    });
                    
                    selectedLabel.classList.remove('bg-blue-50');
                    selectedLabel.classList.add('bg-blue-300');
                    selectedLabel.classList.remove('shadow-sm');
                    selectedLabel.classList.add('shadow-md');
                }
            });

            //saving the answer in the browser local storage
            quizForm.addEventListener('change', function(event) {
                if (event.target.type === 'radio' || event.target.type === 'text') {
                    const questionId = event.target.name;
                    const answer = event.target.value;
                    localStorage.setItem(questionId, answer);

                    if (event.target.type === 'radio') {
                        const selectedLabel = event.target.closest('label');
                        const questionGroup = selectedLabel.closest('.question');
                        const labels = questionGroup.querySelectorAll('label');
                        
                        labels.forEach(label => {
                            label.classList.add('shadow-sm');
                            label.classList.remove('shadow-md');
                            label.classList.remove('bg-blue-300');
                            label.classList.add('bg-blue-50');
                        });
                        
                        selectedLabel.classList.remove('bg-blue-50');
                        selectedLabel.classList.add('bg-blue-300');
                        selectedLabel.classList.remove('shadow-sm');
                        selectedLabel.classList.add('shadow-md');
                    }
                }
            });


});
    </script>
    <style>
        .timer-pulse {
            animation: pulse 0.5s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</x-layout>