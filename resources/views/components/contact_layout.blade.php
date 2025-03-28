<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/navbar-style.css'])
    @vite(['resources/css/contact.css'])
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
        {{-- Upper Navigation --}}
        <div id="upper_nav" class="fixed  h-12 w-full bg-white px-4 z-50 flex justify-center items-center border-b shadow-sm">
            <div class="flex items-center justify-between max-w-lg w-full">
    
                <div class="logo_holder flex justify-start items-center gap-3 pl-4 w-full">
                    <img class="max-w-10" src="{{ asset('logo_icons/logo_head.png') }}" alt="Logo">
                    <p class="nav_text blue_text text-xl font-bold">Meeco</p>
                </div>
                
                <div class="flex items-center gap-2 w-full">
                    
                    <a href="{{ route('profile') }}" class="flex items-center ml-auto">
                        @auth
                            <p class="name mr-1 hidden md:block">{{ auth()->user()->firstname}} {{ auth()->user()->middlename}} {{ auth()->user()->lastname}}</p>
                            @endauth
                            <div class="profile_holder w-9 h-9 rounded-full overflow-hidden border">
                            <img class="w-full h-full object-contain" src="{{ asset('logo_icons/3.jpg') }}" alt="Profile">
                        </div>
                    </a>
                    {{-- for notif --}}
                    <div id="lottie-container" class="w-6 h-6 relative cursor-pointer flex justify-center items-center">
                        <img id="bell_icon" class="w-5 h-5" src="{{asset('logo_icons/notification.svg')}}" alt="">
                        <div id="notification-box" class="text-sm hidden absolute right-0 mt-2 w-64 border border-gray-300 rounded-lg shadow-lg">
                            <div class="p-4 ">
                                <p class="text-gray-700 text-sm ">No new notifications</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
    
        </div>

    <div class=" pt-1 w-full flex justify-center items-center mb-20 mt-12 ">
        {{ $slot ?? '' }}
    </div>

</body>
</html>
