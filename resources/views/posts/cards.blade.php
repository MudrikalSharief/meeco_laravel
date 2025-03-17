<x-layout>
    <div class=" max-w-lg w-full flex flex-col items-center justify-center mx-auto">
        <div class="px-5 pt-16 pb-10 w-full max-w-lg items-center overflow-hidden flex flex-col justify-between h-screen absolute top-0">
            <div class="relative max-w-2xl text-base sm:text-lg md:text-xl overflow-x-hidden shadow-lg bg-white w-full p-5 rounded-3xl border border-gray-200">
                <div id="back_to_reviewer" class="absolute top-2 right-5 w-6 h-6 p-1 rounded-full bg-red-500 cursor-pointer hover:bg-red-700 flex items-center justify-center">
                    <img class="w-3" src="{{ asset('logo_icons/x.svg') }}" alt="close">
                </div>  
                <div id="cards" class="text-gray-800">
                    {{-- reviewer content goes here --}}
                </div>
            </div>
            <div class="mt-3 text-center h-1-5 mb-4">
                <span id="topic_name" class="text-gray-700 text-sm">Topic Title Here</span>
                <div class="card_nav flex justify-center gap-2 items-center mt-1">
                    <div id="prev" class="w-10 p-2 bg-gray-300 rounded-md cursor-pointer hover:bg-gray-500 flex items-center justify-center">
                        <img src="{{ asset('logo_icons/arrow2.svg') }}" alt="left arrow">
                    </div>
                    <div class="font-sans font-semibold text-gray-500">
                        <span id="current_page">
                            {{-- current page in here --}}
                        </span>
                        /
                        <span id="total_page">
                            {{-- total page in here --}}
                        </span>
                    </div>
                    <div id="next" class="w-10 p-2 bg-gray-300 rounded-md cursor-pointer hover:bg-gray-500 flex items-center justify-center">
                        <img src="{{ asset('logo_icons/arrow1.svg') }}" alt="right arrow">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const topicId = urlParams.get('topicId');
            const cards_item = document.getElementById('cards');
            const bottomnav = document.getElementById('bottom_nav');
            bottomnav.classList.add('hidden');
            const back_to_reviewer = document.getElementById('back_to_reviewer');
            const total_page = document.getElementById('total_page');
            const current_page = document.getElementById('current_page');
            const prev = document.getElementById('prev');
            const next = document.getElementById('next');
            let page = 0;

            let cards = [];
            fetch('/disect_reviewer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ topicId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let content = '';
                    const items = data['data'];

                    // Assign the fetched data to the local array
                    items.forEach(item => {
                        cards.push(`<strong>${item[0]}</strong><br>${item[1]}`);
                    });

                    // Show the total number of reviewers
                    total_page.innerHTML = cards.length;
                    current_page.innerHTML = page + 1;

                    // Display the first card
                    updateCard();

                    // Disable the prev button if on the first page
                    if (page === 0) {
                        prev.classList.remove('hover:bg-gray-500', 'cursor-pointer');
                    }

                    // Event listener for the prev button
                    prev.addEventListener('click', function() {
                        if (page > 0) {
                            page -= 1;
                            updateCard();
                            if (page < cards.length - 1) {
                                next.classList.add('hover:bg-gray-500', 'cursor-pointer');
                            }
                            current_page.innerHTML = page + 1;
                        } else {
                            prev.classList.remove('hover:bg-gray-500', 'cursor-pointer');
                        }
                    });

                    // Event listener for the next button
                    next.addEventListener('click', function() {
                        if (page < cards.length - 1) {
                            page += 1;
                            updateCard();
                            if (page === 1) {
                                prev.classList.add('hover:bg-gray-500', 'cursor-pointer');
                            }
                            current_page.innerHTML = page + 1;
                        } else {
                            next.classList.remove('hover:bg-gray-500', 'cursor-pointer');
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));

            // Function to update the card content
            function updateCard() {
                cards_item.innerHTML = cards[page];
            }

            // Click event to go back to reviewer
            back_to_reviewer.addEventListener('click', function() {
                window.location.href = `/reviewer/${topicId}`;
            });
        });
    </script>
</x-layout>