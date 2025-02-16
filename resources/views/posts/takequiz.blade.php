<!-- resources/views/posts/takequiz.blade.php -->
<x-layout>

    <div class="z-50 bg-white px-6 py-3 fixed w-full shadow-lg">
        <p id="title" class=" text-blue-500"></p>
    </div>
    <div class="max-w-2xl h-full mx-auto bg-white  rounded-lg">
        
        <div class="w-full max-w-2xl">

            <div class="quiz-container px-24 pt-24">
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
            let topicId = null;
            
            //get the title of the question
            fetch( `/getquiz/${questionid}`)
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    const title = document.getElementById('title');
                    title.classList.add('cursor-pointer','font-medium');
                    title.innerHTML = `<span>&#129120</span> ${data.question.question_title}`;
                    topicId = data.question.topic_id;
                    console.log(data);
                }
            }).catch(error => console.error('Error:', error));

            const title = document.getElementById('title');
            title.addEventListener('click', function() {
                window.location.href=`/quiz?topicId=${topicId}`;
            });

            const botnav = document.getElementById('bottom_nav');
            botnav.classList.add('hidden');

            questions.forEach((question, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.classList.add('question');
                questionDiv.innerHTML = `
                    <p class ="text-blue-500">${index + 1}) ${question.question_text}</p>
                    <ul>
                        <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="A"> A: ${question.A}</label></li>
                        <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="B"> B: ${question.B}</label></li>
                        <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="C"> C: ${question.C}</label></li>
                        <li><label class = "w-full text-start py-2 px-3 my-2 bg-blue-50 shadow-sm rounded-md flex justify-start gap-2 items-center hover:bg-blue-100 delay-75 hover:transform hover:-translate-y-1 hover:shadow-lg transition duration-300" ><input type="radio" name="question_${index}" value="D"> D: ${question.D}</label></li>
                    </ul>
                `;
                quizForm.appendChild(questionDiv);
            });

            quizForm.addEventListener('change', function(event) {
                if (event.target.type === 'radio') {
                    const selectedLabel = event.target.closest('label');
                    const questionGroup = selectedLabel.closest('.question');
                    const labels = questionGroup.querySelectorAll('label');
                    
                    labels.forEach(label => {
                        label.classList.add('shadow-sm');
                        label.classList.remove('shadow-md');
                        label.classList.remove('bg-blue-200');
                        label.classList.add('bg-blue-50');
                    });
                    
                    selectedLabel.classList.add('bg-blue-50');
                    selectedLabel.classList.add('bg-blue-200');
                    selectedLabel.classList.remove('shadow-sm');
                    selectedLabel.classList.add('shadow-md');
                }
            });

            const submitQuizButton = document.getElementById('submitQuizButton');
            submitQuizButton.addEventListener('click', function(event) {
                

                event.preventDefault();
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
                        scoreModal.classList.add('fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
                        scoreModal.innerHTML = `
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h2 class="text-2xl mb-4">Quiz Score</h2>
                                <p id="scoreText" class="text-lg">Your score: ${data.score}</p>
                                <button id="closeModalButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Close</button>
                            </div>
                        `;
                        document.body.appendChild(scoreModal);

                        const closeModalButton = document.getElementById('closeModalButton');
                        closeModalButton.addEventListener('click', function() {
                            scoreModal.remove();
                        });
                    } else {
                        alert('Failed to submit quiz: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</x-layout>