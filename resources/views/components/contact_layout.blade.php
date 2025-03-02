<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/navbar-style.css'])
    {{-- , 'resources/js/openai.js' --}}
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        body::-webkit-scrollbar {
            width: 0px;
            background: transparent; /* Optional: just to hide the scrollbar */
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        body {
            -ms-overflow-style: none;  /* IE and Edge */
        }
    </style>
</head>
<body class="relative min-h-screen flex flex-col">

    {{-- Upper Navigation --}}
    <div id="upper_nav" class="fixed pr-3 bg-gray-100 h-12 w-full flex items-center justify-between z-50">
        <div class="logo_holder flex justify-start items-center gap-3 pl-4">
            <img class="max-w-10" src="{{ asset('logo_icons/logo_head.png') }}" alt="Logo">
            <p class="nav_text blue_text text-xl font-bold">meeco</p>
        </div>
        


        <a href="{{ route('profile') }}" class="flex items-center ml-auto">
            @auth
                <p class="name mr-1 hidden md:block">{{ auth()->user()->firstname}} {{ auth()->user()->middlename}} {{ auth()->user()->lastname}}</p>
            @endauth
            <div class="profile_holder w-9 h-9 rounded-full overflow-hidden border">
                <img class="w-full h-full object-contain" src="{{ asset('logo_icons/3.jpg') }}" alt="Profile">
            </div>
        </a>
    </div>

    <div class=" pt-1 w-full flex-grow mb-20 mt-12 ">
        {{ $slot ?? '' }}
    </div>

</body>
</html>
