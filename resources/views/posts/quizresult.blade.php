<x-layout>

    <div class="max-w-2xl h-full  mx-auto ] rounded-lg">
        
        <div class="w-full max-w-2xl ">

            <div id="opened_quizz_holder" class=" bg-white rounded-xl h-full w-full px-6 py-3 my-3">
                <!-- Quiz info will be dynamically inserted here -->
            </div>

            <div id="quiz_result" class="quiz-container text-sm mt-2 rounded-lg   px-5 pt-5 pb-3 bg-white">
                <!-- Quiz result will be dynamically inserted here -->
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
            
            // this will go to the reviewer page
            const urlParams = new URLSearchParams(window.location.search);
            const questionId = urlParams.get('questionId');
            const opened_quizz_holder = document.getElementById('opened_quizz_holder');
            let topicId = null;
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
                            <h1 id = "backbutton" class="pt-2 cursor-pointer text-sm text-blue-600 font pb-2 align-middle"><span>&#129120</span> Quiz Information</h1>
                            <div class="flex justify-between mb-2">
                                <p class="text-sm w-full font-semibold "><span class=" text-gray-600 font-normal">Quiz Name<br></span> ${data.question.question_title}</p>
                                <p class="text-sm w-full font-semibold "><span class=" text-gray-600 font-normal">Quiz Type<br></span> ${data.question.question_type}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm w-full font-semibold "><span class=" text-gray-600 font-normal">Question Count<br></span> ${data.question.number_of_question}</p>
                                <p class="text-sm w-full font-semibold "><span class=" text-gray-600 font-normal">Score<br></span> ${data.question.score} / ${data.question.number_of_question}</p>
                            </div>
                            <p class="mt-5 "><span class="text-sm text-blue-600">Start Quiz Noww!!</span></p>
                        `;
                        opened_quizz_holder.appendChild(quizInfoDiv);

                        // Create the start quiz button
                        const startQuizButton = document.createElement('button');
                        startQuizButton.id = data.question.question_id;
                        startQuizButton.classList.add('startQuiz','bg-blue-500', 'mt-2', 'text-white', 'px-4', 'py-2', 'rounded-lg', 'hover:bg-blue-600');
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
                //this is for the summary of the getQuizResult
                fetch(`getquizresult/${questionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);

                        if(quiz_result){ 
                            quiz_result.innerHTML = ''; // Clear previous content

                            if (data.type === 'Mixed') {
                                // Handle mixed quiz type
                                let questionCounter = 1;
                                const mixedQuestions = data.questions;
                                ['multiple_choice', 'true_or_false', 'identification'].forEach(type => {
                                    if (mixedQuestions[type]) {
                                        mixedQuestions[type].forEach((question, index) => {
                                            const questionDiv = document.createElement('div');
                                            questionDiv.classList.add('question');
                                            
                                            if (type === 'multiple_choice') {
                                                questionDiv.innerHTML = `
                                                    <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                                    <p class ="text-green-500">Correct Answer : ${question.answer}</p>
                                                    <ul>
                                                        <li><label class = "choices${index}A w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio"  disabled name="question_${index}" value="A"> A) ${question.A}</label></li>
                                                        <li><label class = "choices${index}B w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="B"> B) ${question.B}</label></li>
                                                        <li><label class = "choices${index}C w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="C"> C) ${question.C}</label></li>
                                                        <li><label class = "choices${index}D w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="D"> D) ${question.D}</label></li>
                                                    </ul>
                                                `;
                                            } else if (type === 'true_or_false') {
                                                questionDiv.innerHTML = `
                                                    <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                                    <p class ="text-green-500">Correct Answer : ${question.answer}</p>
                                                    <ul>
                                                        <li><label class = "choices${index}True w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio"  disabled name="question_${index}" value="True">True </label></li>
                                                        <li><label class = "choices${index}False w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="False"> False </label></li>
                                                    </ul>
                                                `;
                                            } else if (type === 'identification') {
                                                questionDiv.innerHTML = `
                                                    <p class ="text-gray-700 font-semibold">${questionCounter}) ${question.question_text} <span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                                    <p class ="text-green-500">Correct Answer : ${question.answer} </p>
                                                    <ul>
                                                        <li><label class = "choices w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center" ><input class = "w-full  px-1" type="text" disabled name="question_${index}" value="${question.user_answer}"> </label></li>
                                                    </ul>
                                                `;
                                            }
                                            questionCounter++;
                                            quiz_result.appendChild(questionDiv);

                                            // Highlight the correct answer
                                            const correctAnswer = question.answer;
                                            const userAnswer = question.user_answer;
                                            const correctAnswerLabel = questionDiv.querySelectorAll(`input[name="question_${index}"]`);

                                            if (correctAnswerLabel.length === 0) {
                                                console.log('No correct answer found', index);
                                            } else {
                                                correctAnswerLabel.forEach(label => {
                                                    if (type === 'identification') {
                                                        const correctanswer = correctAnswer.toLowerCase();
                                                        const useranswer = userAnswer.toLowerCase();
                                                        const choices = questionDiv.querySelector(`.choices`);
                                                        if (correctanswer === useranswer) {
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
                                                                if (choice.classList.contains('choices' + index + userAnswer)) {
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
                                });
                            } else {
                                // Handle single quiz type
                                data.questions.forEach((question, index) => {
                                    const questionDiv = document.createElement('div');
                                    questionDiv.classList.add('question');
                                
                                    if(data.type === 'Multiple Choice'){
                                        console.log('multiple');
                                        questionDiv.innerHTML = `
                                            <p class ="text-gray-700 font-semibold">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                            <p class ="text-green-500">Correct Answer : ${question.answer}</p>
                                            <ul>
                                                <li><label class = "choices${index}A w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio"  disabled name="question_${index}" value="A"> A) ${question.A}</label></li>
                                                <li><label class = "choices${index}B w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="B"> B) ${question.B}</label></li>
                                                <li><label class = "choices${index}C w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="C"> C) ${question.C}</label></li>
                                                <li><label class = "choices${index}D w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="D"> D) ${question.D}</label></li>
                                            </ul>
                                        `;
                                    }
                                    else if(data.type === 'True or false'){
                                        console.log('TF?');
                                        questionDiv.innerHTML = `
                                            <p class ="text-gray-700 font-semibold">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                            <p class ="text-green-500">Correct Answer : ${question.answer}</p>
                                            <ul>
                                                <li><label class = "choices${index}True w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio"  disabled name="question_${index}" value="True">True </label></li>
                                                <li><label class = "choices${index}False w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="False"> False </label></li>
                                            </ul>
                                        `;
                                    }
                                    else if(data.type === 'Identification'){
                                        const lower = question.user_answer;
                                        console.log('ID?');
                                        questionDiv.innerHTML = `
                                            <p class ="text-gray-700 font-semibold">${index + 1}) ${question.question_text} <span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                            <p class ="text-green-500">Correct Answer : ${question.answer} </p>
                                            <ul>
                                                <li><label class = "choices w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center" ><input class = "w-full  px-1" type="text" disabled name="question_${index}" value="${question.user_answer}"> </label></li>
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
                                    }else{
                                        correctAnswerLabel.forEach(label => {
                                            if(data.type === 'Identification'){
                                                const correctanswer = correctAnswer.toLowerCase();
                                                const useranswer = userAnswer.toLowerCase();
                                                const choices = questionDiv.querySelector(`.choices`);
                                                if(correctanswer === useranswer){
                                                    choices.classList.remove('bg-blue-50');
                                                    choices.classList.remove('bg-red-200');
                                                    choices.classList.add('bg-green-200');
                                                }else{
                                                    choices.classList.remove('bg-blue-50');
                                                    choices.classList.remove('bg-green-200');
                                                    choices.classList.add('bg-red-200');
                                                }
                                            }else{
                                                if (label.value === userAnswer) {
                                                    let letter = label.value;
                                                    const choices = questionDiv.querySelectorAll(`.choices${index}${letter}`);
                                                    choices.forEach(choice => {
                                                        if(choice.classList.contains('choices'+index+userAnswer)){
                                                            choice.classList.remove('bg-blue-50');
                                                            choice.classList.add('bg-red-200');
                                                            if(correctAnswer == userAnswer){
                                                                choice.classList.remove('bg-blue-50');
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
                        quiz_result.innerHTML = `<p class="text-red-500 text-center">You have not taken the quiz yet!</p>`;
                    }
                });
         });    
    </script>


</x-layout>