<x-layout>

    <button id="button" class="p-2 bg-blue-300">Try Payment</button>
    <script>
        document.addEventListener('DOMContentLoaded', function(){

            const button = document.getElementById('button');
            button.addEventListener('click', function(){
                fetch('/Paymongo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
            }).then(response => response.json()
            ).then(data => {
                    console.log('payment code');
                    console.log(data);
                    if(data.success){
                        const responseData = JSON.parse(data.data);
                        const checkoutURL = responseData.data.attributes.checkout_url;
                        window.location.href = checkoutURL;
                        
                    }else{
                        alert("failed in getting checkout url");
                    }

                })
                .catch(error => {
                    console.error('Error:');
                    alert('An error occurred. Please try again.');
                });
            })

        });
    </script>
</x-layout>
