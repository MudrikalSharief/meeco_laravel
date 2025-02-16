<!-- resources/views/posts/takequiz.blade.php -->
<x-layout>

    <div class="max-w-2xl h-full mx-auto pt-6 bg-white  rounded-lg">
        
        <div class="w-full max-w-2xl">

            <div class="flex gap-2 space-x-4 mb-6 px-6">
                <button id="reviewer" class="py-2 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">Reviewer</button>
                <button id="quiz" class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">Quizzes</button>
            </div>
            <hr class="my-3">

            <div class="quiz-container px-24">
                <form id="quizForm">
                    <!-- Questions will be dynamically inserted here -->
                </form>
                <button id="submitQuizButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Quiz</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = @json($questions);
            const questionid = questions[0].question_id;
            const quizForm = document.getElementById('quizForm');
            
            const botnav = document.getElementById('bottom_nav');
            botnav.classList.add('hidden');

            questions.forEach((question, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.classList.add('question');
                questionDiv.innerHTML = `
                    <p>${index + 1}) ${question.question_text}</p>
                    <ul>
                        <li><label><input type="radio" name="question_${index}" value="A"> A: ${question.A}</label></li>
                        <li><label><input type="radio" name="question_${index}" value="B"> B: ${question.B}</label></li>
                        <li><label><input type="radio" name="question_${index}" value="C"> C: ${question.C}</label></li>
                        <li><label><input type="radio" name="question_${index}" value="D"> D: ${question.D}</label></li>
                    </ul>
                `;
                quizForm.appendChild(questionDiv);
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