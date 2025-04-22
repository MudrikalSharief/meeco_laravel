<x-layout>

    <div class="px-4 py-6 h-screen flex flex-col">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex items-center text-xl font-semibold">
                    Quizzes
                </span>
            </div>
            <div class="flex items-center gap-4">
                <button id="viewToggleBtn" class="bg-blue-50 text-blue-600 px-3.5 py-1.5 rounded-full shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2 font-medium border border-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                    </svg>
                    <span id="viewToggleText">Group by Topic</span>
                </button>
            </div>
        </div>

        <div class="w-full h-full flex flex-col items-center">
            <div id="listHeader" class="flex text-xs px-3 justify-between items-center w-full max-w-2xl">
                <p>Quiz Name</p>
            </div>
            <div class="w-full max-w-2xl">
                <div id="ReviewersContainer" class="w-full max-w-2xl"></div>
                <div id="TopicsContainer" class="w-full max-w-2xl hidden"></div>
            </div>  
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const reviewersContainer = document.getElementById('ReviewersContainer');
            const topicsContainer = document.getElementById('TopicsContainer');
            const viewToggleBtn = document.getElementById('viewToggleBtn');
            const viewToggleText = document.getElementById('viewToggleText');
            const listHeader = document.getElementById('listHeader');
            
            let isGroupedView = false;
            
            // Topic colors for visual grouping
            const topicColors = [
                'from-blue-500 to-blue-400',
                'from-purple-500 to-purple-400',
                'from-green-500 to-green-400',
                'from-pink-500 to-pink-400',
                'from-yellow-500 to-yellow-400',
                'from-indigo-500 to-indigo-400',
                'from-red-500 to-red-400',
                'from-teal-500 to-teal-400'
            ];
            
            // Load all quizzes by default
            loadAllQuizzes();
            
            // Toggle between views
            viewToggleBtn.addEventListener('click', function() {
                isGroupedView = !isGroupedView;
                
                if (isGroupedView) {
                    // Animate the transition
                    reviewersContainer.classList.add('opacity-0');
                    setTimeout(() => {
                        reviewersContainer.classList.add('hidden');
                        topicsContainer.classList.remove('hidden');
                        // Fade in with a slight delay
                        setTimeout(() => {
                            topicsContainer.classList.add('opacity-100');
                            topicsContainer.classList.remove('opacity-0');
                        }, 50);
                    }, 200);
                    
                    viewToggleText.textContent = 'Show All';
                    listHeader.classList.add('hidden');
                    
                    // Load quizzes grouped by topic
                    loadQuizzesByTopic();
                } else {
                    // Animate the transition
                    topicsContainer.classList.add('opacity-0');
                    setTimeout(() => {
                        topicsContainer.classList.add('hidden');
                        reviewersContainer.classList.remove('hidden');
                        // Fade in with a slight delay
                        setTimeout(() => {
                            reviewersContainer.classList.add('opacity-100');
                            reviewersContainer.classList.remove('opacity-0');
                        }, 50);
                    }, 200);
                    
                    viewToggleText.textContent = 'Group by Topic';
                    listHeader.classList.remove('hidden');
                }
            });
            
            function loadAllQuizzes() {
                fetch('/quizzes')
                .then(response => response.json())
                .then(data => {
                    if (data.success){
                        reviewersContainer.innerHTML = '';
                        if (Array.isArray(data.questions)) {
                            data.questions.forEach(reviewer => {
                                let reviewerDiv = document.createElement('div');
                                reviewerDiv.classList.add(
                                    'cursor-pointer',
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
                                
                                // Create clickable container
                                reviewerDiv.innerHTML = `
                                    <a href="/quizresult?questionId=${reviewer.question_id}" class="w-full flex justify-between items-center">
                                        <p>${reviewer.question_title}</p>
                                        <span class="text-blue-500">View</span>
                                    </a>
                                `;
                                
                                // Make the entire div clickable
                                reviewerDiv.addEventListener('click', function() {
                                    window.location.href = `/quizresult?questionId=${reviewer.question_id}`;
                                });
                                
                                reviewersContainer.appendChild(reviewerDiv);
                            });
                        } else {
                            console.error('Invalid data format:', data);
                        }
                    }
                })
            }
            
            function loadQuizzesByTopic() {
                fetch('/quizzes-by-topic')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        topicsContainer.innerHTML = '';
                        topicsContainer.classList.add('opacity-0');
                        
                        if (data.quizzesByTopic.length === 0) {
                            topicsContainer.innerHTML = '<div class="text-center py-4">No quizzes available</div>';
                            return;
                        }
                        
                        data.quizzesByTopic.forEach((item, index) => {
                            // Get color for this topic (cycle through colors)
                            const colorClass = topicColors[index % topicColors.length];
                            
                            // Create topic group container
                            let topicGroupDiv = document.createElement('div');
                            topicGroupDiv.classList.add(
                                'w-full',
                                'mb-6',
                                'rounded-lg',
                                'overflow-hidden',
                                'shadow-lg',
                                'border',
                                'border-gray-200'
                            );
                            
                            // Create topic header with gradient background
                            let topicDiv = document.createElement('div');
                            topicDiv.classList.add(
                                'w-full',
                                'bg-gradient-to-r',
                                'text-white',
                                'px-4',
                                'py-3',
                                'font-semibold',
                                'flex',
                                'justify-between',
                                'items-center',
                                'transition-all'
                            );
                            
                            // Add the color classes
                            colorClass.split(' ').forEach(cls => topicDiv.classList.add(cls));
                            
                            // Add topic icon based on topic name
                            const iconSvg = getTopicIcon(item.topic.name);
                            
                            topicDiv.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6">${iconSvg}</div>
                                    <span>${item.topic.name}</span>
                                </div>
                                <div class="text-sm flex gap-1 items-center">
                                    <span class="opacity-80 text-xs">${item.topic.subject_name}</span>
                                    <span class="px-1.5 py-0.5 bg-white bg-opacity-20 rounded text-xs">${item.questions.length} ${item.questions.length === 1 ? 'quiz' : 'quizzes'}</span>
                                </div>
                            `;
                            
                            topicGroupDiv.appendChild(topicDiv);
                            
                            // Create container for quizzes
                            let quizzesContainer = document.createElement('div');
                            quizzesContainer.classList.add('bg-white', 'py-1');
                            
                            // Add quizzes for this topic
                            item.questions.forEach(quiz => {
                                let quizDiv = document.createElement('div');
                                quizDiv.classList.add(
                                    'mx-2',
                                    'my-2',
                                    'text-start',
                                    'py-2',
                                    'px-3',
                                    'shadow-sm',
                                    'rounded-md',
                                    'flex',
                                    'justify-between',
                                    'bg-white',
                                    'items-center',
                                    'hover:bg-blue-50',
                                    'delay-75',
                                    'hover:transform',
                                    'hover:-translate-y-1',
                                    'hover:shadow-md',
                                    'transition',
                                    'duration-300',
                                    'border',
                                    'border-gray-100',
                                    'cursor-pointer'
                                );
                                
                                // Create clickable container
                                quizDiv.innerHTML = `
                                    <a href="/quizresult?questionId=${quiz.question_id}" class="w-full flex justify-between items-center">
                                        <p>${quiz.question_title}</p>
                                        <span class="text-blue-500">View</span>
                                    </a>
                                `;
                                
                                // Make the entire div clickable
                                quizDiv.addEventListener('click', function() {
                                    window.location.href = `/quizresult?questionId=${quiz.question_id}`;
                                });
                                
                                quizzesContainer.appendChild(quizDiv);
                            });
                            
                            topicGroupDiv.appendChild(quizzesContainer);
                            topicsContainer.appendChild(topicGroupDiv);
                        });
                        
                        // Fade in the topics container
                        setTimeout(() => {
                            topicsContainer.classList.remove('opacity-0');
                        }, 100);
                    } else {
                        console.error('Failed to load quizzes by topic');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
            }
            
            // Helper function to get topic icons
            function getTopicIcon(topicName) {
                const topic = topicName.toLowerCase();
                
                if (topic.includes('math') || topic.includes('calculus') || topic.includes('algebra')) {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6z" /></svg>';
                } else if (topic.includes('science') || topic.includes('physics') || topic.includes('chemistry') || topic.includes('biology')) {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>';
                } else if (topic.includes('history') || topic.includes('social')) {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>';
                } else if (topic.includes('english') || topic.includes('language') || topic.includes('literature')) {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>';
                } else {
                    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>';
                }
            }
        });
    </script>
</x-layout>