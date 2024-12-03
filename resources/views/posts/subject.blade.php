<x-layout>

    <div class=" p-3 w-full h-full">
        <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects</h1>
        <div id="subjectsContainer" class="w-full max-w-2xl"></div>
        <p id="noSubjectsMessage" class="text-gray-500 mt-2 hidden">No Subjects to Show</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route('subjects.list') }}')
                .then(response => response.json())
                .then(data => {
                    const subjectsContainer = document.getElementById('subjectsContainer');
                    const noSubjectsMessage = document.getElementById('noSubjectsMessage');
                    if (data.subjects && data.subjects.length > 0) {
                        data.subjects.forEach((subject, index) => {
                            const subjectButton = document.createElement('a');
                            subjectButton.href = `/subjects/${subject.name}`;
                            subjectButton.innerHTML = `<button class="w-full border text-start py-2 px-3 my-2 shadow-md rounded-md"> ${subject.name}</button>`;
                            subjectsContainer.appendChild(subjectButton);
                        });
                    } else {
                        noSubjectsMessage.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error fetching subjects:', error));
        });
    </script>
</x-layout>