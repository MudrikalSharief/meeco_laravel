<x-layout>
    <div class="mt-2 ml-3">Chat the Chatgpt?</div>
    <form action="">
        <input type="text" id="message" name="message" class="border">
        <button type="submit">Send</button>
    </form>
    
    <script>
        $("document").ready(function() {
            $('form').submit(function(event) {
                event.preventDefault();
                $("form #message")..prop('disabled', true);
                $("form button").prop('disabled', true);

                $.ajax({
                    url:"/openai.test",
                    method::"POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data:{
                        "content": $("form #message").val();
                    }
                }) .done(function(res)){
                    //populate sending image
                    $(".message > .message").last().after('<div class="right message">') + 
                        '<p>' + $("form #message").val() + '</p>' + </div>

                    $(".messages > .images").last().after('<div class="left images">') + 
                      '<p>'  + res.choices[0].message.content + '<p>' </div>

                    //clean up
                    $("form #message").val('');
                    $(document).scrollTop($(document).height());

                    $("form #message")..prop('disabled', false);
                    $("form button").prop('disabled', false);
                }

            });
        });
    </script>
</x-layout>