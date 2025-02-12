<x-layout>

    <div class="p-6 w-full h-full flex flex-col items-center">
        <div class="w-full max-w-2xl">
            <div class="flex justify-between items-center">
                <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Quiz</h1>
                <button id="addQuizButton" class="mb-3 bg-blue-500 text-white py-2 px-4 rounded">New Quiz</button>
            </div>
        <div id="quizContainer" class="w-full max-w-2xl"></div>
        <p id="noSubjectsMessage" class="text-gray-500 mt-2 text-center">No Quiz to Show</p>
    </div>
   

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const newQuizButton = document.getElementById('addQuizButton');
    const urlParams = new URLSearchParams(window.location.search);
    const topicId = urlParams.get('topicId');

    if (!newQuizButton) {
        console.error('Button not found');
        return;
    }

    newQuizButton.addEventListener('click', function() {
        if (!topicId) {
            document.getElementById('quizContainer').innerHTML = `<p style="color:red;">No topic ID found in URL.</p>`;
            return;
        }

        fetch(`/generate-quiz/${topicId}`)
        .then(response => response.json())
        .then(data => {
            if (!data.choices || !data.choices.length) {
                document.getElementById('quizContainer').innerHTML = `<p style="color:red;">Failed to generate quiz.</p>`;
                return;
            }

            try {
                // Parse the OpenAI response (string to JSON)
                const quizData = JSON.parse(data.choices[0].message.content);
                
                let quizHtml = "<h3>Generated Quiz</h3>";
                quizData.questions.forEach((q, index) => {
                    quizHtml += `<p><b>Q${index + 1}:</b> ${q.question}</p>`;
                    Object.keys(q.choices).forEach(key => {
                        quizHtml += `<label>
                            <input type="radio" name="q${index}" value="${key}"> ${key}. ${q.choices[key]}
                        </label><br>`;
                    });
                    quizHtml += "<br>";
                });
                document.getElementById('quizContainer').innerHTML = quizHtml;
            } catch (error) {
                document.getElementById('quizContainer').innerHTML = `<p style="color:red;">Error parsing quiz data.</p>`;
                console.error("Parsing error:", error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

</script>

</x-layout>