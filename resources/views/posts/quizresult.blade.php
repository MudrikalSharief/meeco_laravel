<x-layout>

    <div class="max-w-2xl h-full mx-auto  bg-white  rounded-lg">
        
        <div class="w-full max-w-2xl">

            <div id="opened_quizz_holder" class=" bg-blue-100 h-full w-full px-6 py-3">
                <!-- Quiz info will be dynamically inserted here -->
            </div>

            <div id="quiz_result" class="quiz-container lg:px-16 md:px-16 sm:px-10  px-5 pt-5 pb-3">
                <!-- Quiz result will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            const bottom_nav = document.getElementById('bottom_nav');
            bottom_nav.classList.add('hidden');

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
                            <h1 id = "backbutton" class=" cursor-pointer text-blue-600 font pb-2 align-middle"><span>&#129120</span> Quiz Information</h1>
                            <p><span class="text-sm text-gray-600">Quiz Name:</span> ${data.question.question_title}</p>
                            <p><span class="text-sm text-gray-600">Quiz Type:</span> ${data.question.question_type}</p>
                            <p><span class="text-sm text-gray-600">Question Count:</span> ${data.question.number_of_question}</p>
                            <p><span class="text-sm text-gray-600">Score:</span> ${data.question.score} / ${data.question.number_of_question}</p>
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

                            data. questions.forEach((question, index) => {
                                const questionDiv = document.createElement('div');
                                questionDiv.classList.add('question');
                            
                                if(data.type === 'Multiple Choice'){
                                    console.log('mulitiple');
                                    questionDiv.innerHTML = `
                                        <p class ="text-blue-500">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
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
                                        <p class ="text-blue-500">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class = "choices${index}True w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio"  disabled name="question_${index}" value="True">True </label></li>
                                            <li><label class = "choices${index}False w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center " ><input type="radio" disabled name="question_${index}" value="False"> Flase </label></li>
                                        </ul>
                                    `;
                                }
                                else if(data.type === 'Identification'){
                                    const lower = question.user_answer;
                                    console.log('ID?');
                                    questionDiv.innerHTML = `
                                        <p class ="text-blue-500">${index + 1}) ${question.question_text} <span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
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
                                        if(!data.type === 'Identification'){
                                            if (label.value === userAnswer) {
                                                // console.log('User :', label.value,' user:', userAnswer);
                                                let letter = label.value;
                                                const choices = questionDiv.querySelectorAll(`.choices${index}${letter}`);
            
                                                choices.forEach(choice => {
                                                    // console.log('choices'+index+userAnswer)
                                                    if(choice.classList.contains('choices'+index+userAnswer)){
                                                        choice.classList.remove('bg-blue-50');
                                                        choice.classList.add('bg-red-200');
                                                        label.checked = true;
                                                    }
                                                });
                                            }
            
                                            if (label.value === correctAnswer) {
                                                // console.log('Correct :', label.value,' user:', correctAnswer);
                                                let letter = label.value;
                                                const choices = questionDiv.querySelectorAll(`.choices${index}${letter}`);
            
                                                choices.forEach(choice => {
                                                    // console.log('choices'+index+correctAnswer)
                                                    if(choice.classList.contains('choices'+index+correctAnswer)){
                                                        
                                                        choice.classList.remove('bg-blue-50');
                                                        choice.classList.remove('bg-red-200');
                                                        choice.classList.add('bg-green-400');
                                                    }
                                                });
                                            }
                                        }else{
                                            correctanswer = correctAnswer.toLowerCase();
                                            useranswer = userAnswer.toLowerCase();
                                            

                                            
                                            const choices = questionDiv.querySelector(`.choices`);
                                            if(correctanswer === useranswer){
                                                choices.classList.remove('bg-blue-50');
                                                choices.classList.remove('bg-red-200');
                                                choices.classList.add('bg-green-400');
                                                
                                             }else{
                                                choices.classList.remove('bg-blue-50');
                                                choices.classList.remove('bg-green-400');
                                                choices.classList.add('bg-red-200');
                                            }
                                                
                                            
                                        }

                                    });
                                }
                            
                            });
                        }
                    } else {
                        console.log("the user did not take the quiz yet")
                        quiz_result.innerHTML = `<p class="text-red-500 text-center">You have not taken the quiz yet!</p>`;
                    }
                });
         });    
    </script>


</x-layout>