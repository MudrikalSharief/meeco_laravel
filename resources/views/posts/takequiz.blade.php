<x-layout>

    <div class=" z-50 sticky top-12 bg-white px-6 py-3 w-full shadow-lg flex items-center justify-center">
        <p id="title" class=" text-blue-500 w-full max-w-2xl items-center lg:px-16 md:px-16 sm:px-10  px-5 py-1"></p>
    </div>
    <div class="max-w-2xl h-full mx-auto bg-white rounded-lg">
        
        <div class="w-full max-w-2xl">

                <div class="quiz-container lg:px-16 md:px-16 sm:px-10  px-5 pt-5 pb-3">
                    <form id="quizForm" method="POST">
                        <!-- Questions will be dynamically inserted here -->
                    </form>
                    <button id="submitQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Quiz</button>
                </div>
                

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                                <p class ="-4 text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="A"> A) ${question.A}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="B"> B) ${question.B}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="C"> C) ${question.C}</label></li>
                                    <li><label class = "multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="D"> D) ${question.D}</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);
                            questionCounter++;
                        });
                    }
                    else if(quiztype === 'True or false'){
                        console.log('TF?');
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="True">  True</label></li>
                                    <li><label class = "true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="False">  False</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);
                            questionCounter++;
                        });
                    }
                    else if(quiztype === 'Identification'){
                        console.log('akwdoadok');
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "identification w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input class = "w-full  px-1" type="text" placeholder="Input Answer" name="question_${index}" > </label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);
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
                                        <p class ="text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_A"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_A" value="A"> A) ${question.A}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_B"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_B" value="B"> B) ${question.B}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_C"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_C" value="C"> C) ${question.C}</label></li>
                                            <li><label class="multiple_choice w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_D"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_D" value="D"> D) ${question.D}</label></li>
                                        </ul>
                                    `;
                                } else if (type === 'true_or_false') {
                                    questionDiv.innerHTML = `
                                        <p class ="text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_True"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_True" value="True">  True</label></li>
                                            <li><label class="true_or_false w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}_False"><input type="radio" name="question_${questionCounter}" id="question_${questionCounter}_False" value="False">  False</label></li>
                                        </ul>
                                    `;
                                } else if (type === 'identification') {
                                    questionDiv.innerHTML = `
                                        <p class ="text-blue-500">${questionCounter}) ${question.question_text}<span id="q${questionCounter}" class = "text-red-500 pl-2"></span></p>
                                        <ul>
                                            <li><label class="identification w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" for="question_${questionCounter}"><input class="w-full px-1" type="text" placeholder="Input Answer" name="question_${questionCounter}" id="question_${questionCounter}"></label></li>
                                        </ul>
                                    `;
                                }

                                quizForm.appendChild(questionDiv);
                                questionCounter++;
                            });
                        }
                    });
                }

    
                    const submitQuizButton = document.getElementById('submitQuizButton');
                    const quizContainer = document.querySelector('.quiz-container');
                    const questionDivs = quizContainer.querySelectorAll('.question');
                    
                    questionCounter = 1; // Initialize questionCounter for validation

                    submitQuizButton.addEventListener('click', function(event) {
                        // Prevent the default form submission
                        event.preventDefault();

                        //validation
                        // let index = 0;
                        // let clear = 0;
                        // let total = 0;

                        // if(quiztype === 'Identification'){
                        //     questionDivs.forEach(questionDivs =>{
                        //     total++;
                        //     const labels = questionDivs.querySelectorAll(`input[name="question_${index}"]`);
                        //     let answered = 0;        
                        //     labels.forEach(label => {
                                
                        //         if(label.value.trim() != ""){
                        //             answered++;
                        //             clear++;
                        //         }else{
                        //             label.value = "";
                        //         }
                                
                        //         if(answered == 0){
                        //             const q = document.getElementById(`q${questionCounter}`);
                        //             q.innerHTML = "Please answer this question";
                        //         }else{
                        //             const q = document.getElementById(`q${questionCounter}`);
                        //             q.innerHTML = "";
                        //         }
                        //     });
                        //     console.log(index, answered,clear);
                        //     index++;
                        //     questionCounter++; // Increment questionCounter
                        
                        // })
                        // }
                        // else{

                        //     questionDivs.forEach(questionDivs =>{
                        //         total++;
                        //         const labels = questionDivs.querySelectorAll(`input[name="question_${index}"]`);
                        //         let answered = 0;        
                        //         labels.forEach(label => {
                        //             if(label.checked){
                        //                 answered++;
                        //                 clear++;
                        //             }
                        //             if(answered == 0){
                        //                 const q = document.getElementById(`q${questionCounter}`);
                        //                 q.innerHTML = "Please answer this question";
                        //             }else{
                        //                 const q = document.getElementById(`q${questionCounter}`);
                        //                 q.innerHTML = "";
                        //             }
                        //         });
                        //         console.log(index, answered,clear);
                        //         index++;
                        //         questionCounter++; // Increment questionCounter
                            
                        //     })
                        // }
                        
                        // if (clear < total) {
                        //     const ValidateModal = document.createElement('div');
                        //     ValidateModal.id = 'ValidateModal';
                        //     ValidateModal.classList.add('z-50','fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                        //     ValidateModal.innerHTML = `
                        //             <div class="bg-white p-6 rounded-lg shadow-lg">
                        //                 <h2 class="text-2xl mb-4">Oh No!</h2>
                        //                 <p id="scoreText" class="text-lg">Please Answer all Questions</p>
                        //                 <button id="closeModalButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
                        //             </div>
                        //         `;
                        //         document.body.appendChild(ValidateModal);
                                
                        //         const closeModalButton = document.getElementById('closeModalButton');
                        //         if(closeModalButton){
                        //             closeModalButton.addEventListener('click', function() {
                        //                 ValidateModal.remove();
                        //             });
                        //         }
                        //         return;
                        // }
                        
                        //if validaten continue
                        const formData = new FormData(quizForm);
                        quiztype = data.question.question_type;
                        console.log(quiztype);
                        const answers = {
                            multiple_choice: [],
                            true_or_false: [],
                            identification: []
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

                    
                    
                    // Add the questionId to the answers object
                    answers.questionId = questionid;
                    
                        console.log(answers);
                        fetch('/submitquiz', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(answers)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data);
                                const scoreModal = document.createElement('div');
                                scoreModal.id = 'scoreModal';
                                scoreModal.classList.add('z-50','fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                                scoreModal.innerHTML = `
                                    <div class="bg-white p-6 rounded-lg shadow-lg">
                                        <h2 class="text-2xl mb-4">Congratulation!!</h2>
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
                        .catch(error => console.error('Error:', error));
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


        });
    </script>
</x-layout>