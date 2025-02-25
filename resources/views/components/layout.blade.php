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

    <!-- Bottom Navigation -->
    <nav id="bottom_nav" class="z-50 bg-white fixed bottom-4 left-1/2 transform -translate-x-1/2 w-11/12 max-w-xl h-16 flex flex-row justify-between items-center border-t shadow-[0_-3px_10px_1px_rgba(50,50,50,0.15)] rounded-lg">
        <ul id="menu" class="flex flex-row items-center space-x-4 px-4 mx-auto">
            <a href="{{ route('capture') }}" class="bottom_nav block menu-item rounded-lg {{ Request::routeIs('capture') ? 'bg-blue-100' : '' }}{{ Request::routeIs('extracted') ? 'bg-blue-100' : '' }}">
                <li class="flex justify-start items-center gap-3 p-2">
                    <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/camera-viewfinder.svg') }}" alt="Icon"></div>
                    <p class="nav_text blue_text font-normal hidden md:block">Convert New</p>
                </li>
            </a>
            <a href="{{ route('subject') }}" class="bottom_nav block menu-item rounded-lg {{ Request::routeIs('subjects') ? 'bg-blue-100' : '' }}{{ Request::routeIs('subject') ? 'bg-blue-100' : '' }}">
                <li class="flex justify-start items-center gap-3 p-2">
                    <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/books.svg') }}" alt="Icon"></div>
                    <p class="nav_text blue_text font-normal hidden md:block">Subjects</p>
                </li>
            </a>
            <a href="{{ route('deleted') }}" class="bottom_nav block menu-item rounded-lg {{ Request::routeIs('deleted') ? 'bg-blue-100' : '' }}">
                <li class="flex justify-start items-center gap-3 p-2">
                    <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/recycle-bin.svg') }}" alt="Icon"></div>
                    <p class="nav_text blue_text font-normal hidden md:block">Deleted</p>
                </li>
            </a>
            <a href="{{ route('upgrade') }}" class="bottom_nav block menu-item rounded-lg {{ Request::routeIs('upgrade') ? 'bg-blue-100' : '' }}">
                <li class="flex justify-start items-center gap-3 p-2">
                    <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/up.svg') }}" alt="Icon"></div>
                    <p class="nav_text blue_text font-normal hidden md:block">Upgrade</p>
                </li>
            </a>
        </ul>
    </nav>

    <div class=" pt-1 w-full flex-grow mb-20 mt-12">
        {{ $slot ?? '' }}
    </div>

</body>
</html>
