<x-layout>

    <div class=" p-3 w-full h-full">
        <div class="flex items-center">
            <a href="{{ route('subject')}}"><h1 class="py-3 px-2 text-xl font-bold text-blue-500">Subjects </h1></a>
            <h2 class=" font-semibold text-xl text-blue-500"> > Topics</h2>
        </div>
        
        <?php
            $count = 5;
        ?>
        @if ($count>0)
            @for ($i = 0; $i < $count; $i++)
                <a  href="#"><button class=" w-full max-w-2xl border text-start py-2 px-3 my-2 shadow-md rounded-md">Topics {{ $i+1}}</button></a>
            @endfor    
        @else
            <p class="text-gray-500 mt-2">No Subjects to Show</p>
        @endif
    </div>
   
</x-layout>