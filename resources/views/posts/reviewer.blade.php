<x-layout>
    <div class="flex items-center">
        <a href="{{ route('subject') }}">
            <h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects</h1>
        </a>
        <a href="#">
            <h2 class="font-semibold text-xl text-blue-500"> > Topics</h2>
        </a>
    </div>

    <div class="max-w-3xl mx-auto my-10 p-6 bg-white  rounded-lg ">
        <!-- Buttons -->
        <div class="flex gap-2 space-x-4 mb-6">
            <button class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">Reviewer</button>
            <button class="py-2 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">Quizzes</button>
        </div>

        <!-- Content Header -->
        <hr class="my-3">
        <div class="flex items-center justify-between mb-4">
            <h1 class="TITLE text-xl font-bold text-gray-800">Grammar</h1>
            <button id="toggleButton" class="text-blue-500 text-sm font-medium rounded-lg hover:underline">Raw text</button>
        </div>

        <!-- Scrollable Content Box -->
        <div class="Reviewer border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            <p class="font-semibold">Parts of Speech:</p>
            <p>
                Nouns: Words that name people, places, things, or ideas.<br>
                Pronouns: Words that take the place of nouns.<br>
                Verbs: Action words or words that express a state of being.<br>
                Adjectives: Words that describe or modify nouns.<br>
                Adverbs: Words that describe verbs, adjectives, or other adverbs.<br>
                Prepositions: Words that show the relationship between a noun and another word.<br>
                Conjunctions: Words that connect words, phrases, or clauses.<br>
                Interjections: Words that express strong emotions.
            </p>
            <p class="font-semibold mt-4">Sentence Structures:</p>
            <p>
                Simple Sentence: Contains one independent clause.<br>
                Compound Sentence: Contains two or more independent clauses.<br>
                Complex Sentence: Contains one independent clause and at least one dependent clause.<br>
                Compound-Complex Sentence: Contains two or more independent clauses and at least one dependent clause.
            </p>
            <p class="font-semibold mt-4">Tenses:</p>
            <p>
                Present Tense: Describes actions happening now.<br>
                Past Tense: Describes actions that happened before.<br>
                Future Tense: Describes actions that will happen.<br>
                Present Perfect Tense: Describes actions that occurred at an unspecified time or started in the past and continue to the present.<br>
                Past Perfect Tense: Describes actions completed before another action.<br>
                Future Perfect Tense: Describes actions that will be completed by a certain future time.
            </p>
            <p class="font-semibold mt-4">Subject-Verb Agreement:</p>
            <p>The subject and verb must agree in number. Singular subjects take singular verbs, while plural subjects take plural verbs.</p>
        </div>

        <div class="Rawtext hidden border border-blue-500 rounded-lg bg-blue-50 p-6 overflow-y-scroll";>
            <h1>Rawtext here</h1>
         </div>
    </div>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            const reviewerSection = document.querySelector('.Reviewer');
            const rawTextSection = document.querySelector('.Rawtext');
            const TITLE = document.querySelector('.TITLE');
            const button = document.getElementById('toggleButton');

            reviewerSection.classList.toggle('hidden');
            rawTextSection.classList.toggle('hidden');

            if (reviewerSection.classList.contains('hidden')) {
                button.textContent = 'Reviewer';
                TITLE.textContent="Raw Text"
            } else {
                button.textContent = 'Raw text';
                TITLE.textContent="Grammar"
            }
        });
    </script>

</x-layout>
