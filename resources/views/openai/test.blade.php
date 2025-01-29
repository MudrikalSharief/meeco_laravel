<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ env('APP_NAME') }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="h-screen w-full">
    <div class="chat">
        {{-- Chat --}}
        <div class="messages">
            <div class="left message">
                <p>Start Chatting</p>
            </div>
        </div>
        {{-- End Chat --}}

        {{-- Footer --}}
        <div class="bottom">
            <form>
                @csrf   
                <input type="text" id="message" name="message" placeholder="Type a message" autocomplete="off">
                <button type="submit" ></button>
            </form>
        </div>
        {{-- End Footer --}}

    </div>

    <script src="{{ route('openai.js') }}"></script>

    {{-- INCORRECT API KEY --}}
</body>
</html>

