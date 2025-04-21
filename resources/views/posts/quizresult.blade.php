<x-layout>
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Quiz Results</h1>
        <div id="quiz_info" class="mb-6">
            <!-- Quiz info will be dynamically inserted here -->
        </div>
        <div id="quiz_result" class="quiz-container text-sm mt-2 rounded-lg px-5 pt-5 pb-3 bg-white">
            <!-- Quiz result will be dynamically inserted here -->
        </div>
        <div class="mt-4">
            <p class="text-lg font-semibold">Time Taken: <span id="timeTaken" class="text-blue-500"></span></p>
        </div>
        <div class="flex justify-center mt-6">
            <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Back to Home</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const questionId = urlParams.get('questionId');
            const quizInfoDiv = document.getElementById('quiz_info');
            const quizResultDiv = document.getElementById('quiz_result');
            const timeTakenSpan = document.getElementById('timeTaken');

            // Fetch quiz result data
            fetch(`/getquizresult/${questionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Display quiz info
                        quizInfoDiv.innerHTML = `
                            <h2 class="text-xl font-bold mb-2">Quiz Information</h2>
                            <p class="text-gray-700"><strong>Quiz Title:</strong> ${data.questions[0]?.question_title || 'N/A'}</p>
                            <p class="text-gray-700"><strong>Quiz Type:</strong> ${data.type}</p>
                            <p class="text-gray-700"><strong>Score:</strong> ${data.score}</p>
                        `;

                        // Display time taken
                        const timeTaken = data.timer_result;
                        const minutes = Math.floor(timeTaken / 60);
                        const seconds = timeTaken % 60;
                        timeTakenSpan.textContent = `${minutes}m ${seconds}s`;

                        // Display quiz results
                        quizResultDiv.innerHTML = `
                            <h2 class="text-lg font-semibold mb-2">Quiz Results</h2>
                            <p class="text-gray-700">Your answers and the correct answers will be displayed here.</p>
                        `;
                    } else {
                        quizResultDiv.innerHTML = `
                            <p class="text-red-500 font-semibold">Failed to load quiz results: ${data.message}</p>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching quiz results:', error);
                    quizResultDiv.innerHTML = `
                        <p class="text-red-500 font-semibold">An error occurred while loading the quiz results.</p>
                    `;
                });
        });
    </script>
</x-layout>