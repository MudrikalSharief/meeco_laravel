<h1>This is a test for Gemma 3 API</h1>

<meta name="csrf-token" content="{{ csrf_token() }}">

<button>Click me to test</button>

<div id="result" class="mt-3 bg-blue-500 p-3 text-white">
    {{-- //The result goes here --}}
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelector('button').addEventListener('click', function(){
            fetch('/api/gemma3',{ 
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        document.querySelector('#result').innerHTML = "The Reviewer Generation is a Success";
                    }else{
                        document.querySelector('#result').innerHTML = 'Error: in creating reviewer ';
                    }
                })
                .catch(error => {
                console.error('Error fetching data:', error);
                document.querySelector('#result').innerHTML = 'Error: ' + error.message;
            });
        })
    })
</script>