<x-layout>

    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center text-xl font-semibold">
                    Reviewers
                </span>
            </div>
        </div>

        <div class=" w-full h-full flex flex-col items-center">
            <div class="flex text-xs px-3 justify-between items-center w-full max-w-2xl">
                <p>Reviewer Name</p>
            </div>
            <div class="w-full max-w-2xl">
            <div id="ReviewersContainer" class="w-full max-w-2xl"></div>
        </div>  

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            fetch('reviewers')
            .then(response => response.json())
            .then(data => {
                if (data.success){

                    let reviewersContainer = document.getElementById('ReviewersContainer');
                    if (Array.isArray(data.reviewer)) {
                        data.reviewer.forEach(reviewer => {
                            let reviewerDiv = document.createElement('div');
                            reviewerDiv.classList.add(
                                'curosor-pointer',
                                'w-full',
                                'text-start',
                                'py-2',
                                'px-3',
                                'my-2',
                                'shadow-md',
                                'rounded-md',
                                'flex',
                                'justify-between',
                                'bg-white',
                                'items-center',
                                'hover:bg-blue-50',
                                'delay-75',
                                'hover:transform',
                                'hover:-translate-y-1',
                                'hover:shadow-lg',
                                'transition',
                                'duration-300'
                            );
                            reviewerDiv.innerHTML = `
                                <p>${reviewer.name}</p>
                                <a href="/reviewer/${reviewer.topic_id}" class="text-blue-500">View</a>
                            `;
                            reviewersContainer.appendChild(reviewerDiv);
                        });
                    } else {
                        console.error('Invalid data format:', data);
                    }
                }
            })
        })
    </script>
</x-layout>