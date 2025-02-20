<!-- resources/views/posts/takequiz.blade.php -->
<x-layout>

    <div class=" z-50 sticky top-12 bg-white px-6 py-3 w-full shadow-lg">
        <p id="title" class=" text-blue-500"></p>
    </div>
    <div class="max-w-2xl h-full mx-auto bg-white rounded-lg">
        
        <div class="w-full max-w-2xl">

            <div class="quiz-container lg:px-16 md:px-16 sm:px-10  px-5 pt-5 pb-3">
                <form id="quizForm">
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
            const questionid = questions[0].question_id;
            const quizForm = document.getElementById('quizForm');
            let quiztype = null;
            let topicId = null;
            
            //get the title of the question
            fetch( `/getquiz/${questionid}`)
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    const title = document.getElementById('title');
                    title.classList.add('cursor-pointer','font-medium');
                    title.innerHTML = `<span>&#129120</span> ${data.question.question_title}`;
                    topicId = data.question.question_id;
                    quiztype = data.question.question_type;

                    if(quiztype === 'Multiple Choice'){
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-blue-500">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="A"> A) ${question.A}</label></li>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="B"> B) ${question.B}</label></li>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="C"> C) ${question.C}</label></li>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="D"> D) ${question.D}</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);
                        });
                    }
                    else if(quiztype === 'True or false'){
                        questions.forEach((question, index) => {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('question');
                            questionDiv.innerHTML = `
                                <p class ="text-blue-500">${index + 1}) ${question.question_text}<span id="q${index+1}" class = "text-red-500 pl-2"></span></p>
                                <ul>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="True"> A) True</label></li>
                                    <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="False"> B) False</label></li>
                                </ul>
                            `;
                            quizForm.appendChild(questionDiv);
                        });
                    }

                    
                    const submitQuizButton = document.getElementById('submitQuizButton');
                    const quizContainer = document.querySelector('.quiz-container');
                    const questionDivs = quizContainer.querySelectorAll('.question');
                    

                    submitQuizButton.addEventListener('click', function(event) {
                        //validation
                        event.preventDefault();
                        let index = 0;
                        let clear = 0;
                        let total = 0;
                        questionDivs.forEach(questionDivs =>{
                            total++;
                            const labels = questionDivs.querySelectorAll(`input[name="question_${index}"]`);
                            let answered = 0;        
                            labels.forEach(label => {
                                if(label.checked){
                                    answered++;
                                    clear++;
                                }
                                if(answered == 0){
                                    const q = document.getElementById(`q${index+1}`);
                                    q.innerHTML = "Please answer this question";
                                }else{
                                    const q = document.getElementById(`q${index+1}`);
                                    q.innerHTML = "";
                                }
                            });
                            console.log(index, answered,clear);
                            index++;
                        
                        })
                        
                        if (clear < total) {
                            const ValidateModal = document.createElement('div');
                            ValidateModal.id = 'ValidateModal';
                            ValidateModal.classList.add('z-50','fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                            ValidateModal.innerHTML = `
                                    <div class="bg-white p-6 rounded-lg shadow-lg">
                                        <h2 class="text-2xl mb-4">Oh No!</h2>
                                        <p id="scoreText" class="text-lg">Please Answer all Questions</p>
                                        <button id="closeModalButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
                                    </div>
                                `;
                                document.body.appendChild(ValidateModal);
                                
                                const closeModalButton = document.getElementById('closeModalButton');
                                if(closeModalButton){
                                    closeModalButton.addEventListener('click', function() {
                                        ValidateModal.remove();
                                    });
                                }
                                return;
                        }

                            //if validaten continue
                        const formData = new FormData(quizForm);
                        const answers = {};
                        formData.forEach((value, key) => {
                            answers[key] = value;
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