    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ env('APP_NAME') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-screen w-full">
    
        {{-- Upper Navigation --}}
        <div id="upper_nav" class=" fixed md:pl-52 pl-16 pr-3 bg-gray-100 h-12 w-full flex items-center justify-between md:justify-end">
            <div id="burger" class="burger_holder w-7 cursor-pointer md:hidden">
                <img src="{{ asset('logo_icons/menu-burger.svg') }}" alt="Menu">
            </div>
            <a href=" {{route('profile')}}" class="flex items-center">
                <p class="name mr-1">{{ auth()->user()->name }}</p>
                <div class="profile_holder w-9 h-9 rounded-full overflow-hidden border">
                    <img class="w-full h-full object-contain " src="{{ asset('logo_icons/3.jpg') }}" alt="Profile">
                </div>
            </a>
        </div>
    
        {{-- Sidebar --}}
        <nav id="sidebar" class=" z-50 md:w-52 bg-white fixed w-14  h-screen pb-2 px-2 flex flex-col justify-between items-center border-r shadow-[-3px_0_10px_1px_rgba(50,50,50,0.15)]">
            <div class="logo_holder flex-col justify-between items-center w-full">
                <div id="sidebar-logo" class="logo_holder flex justify-start items-center gap-3 w-full">
                    <img class="max-w-10" src="{{ asset('logo_icons/logo_head.png') }}" alt="Logo">
                    <p class="md:block nav_text blue_text text-xl font-bold hidden">meeco</p>
                </div>

                <ul id="menu" class="mt-7 flex flex-col items-center space-y-1 w-full cursor-pointer">
                    <a href=" {{ route('capture')  }}" class=" block w-full menu-item rounded-lg {{ Request::routeIs('capture') ? 'bg-blue-100' : '' }}">
                        <li class=" flex justify-start items-center gap-3 p-2  ">
                            <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/camera-viewfinder.svg') }}" alt="Icon"></div>
                            <p class="md:block nav_text blue_text font-medium hidden">Convert New</p>
                        </li>
                    </a>
                    <a href="{{ route('subject')}}" class=" block w-full menu-item rounded-lg {{ Request::routeIs('subject') ? 'bg-blue-100' : '' }}">
                        <li class=" flex justify-start items-center gap-3  p-2 ">
                            <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/books.svg') }}" alt="Icon"></div>
                            <p class="md:block nav_text blue_text font-medium hidden">Subjects</p>
                        </li>
                    </a>
                    <a href="{{ route('deleted')}}" class=" block w-full menu-item rounded-lg {{ Request::routeIs('deleted') ? 'bg-blue-100' : '' }}">
                        <li class=" flex justify-start items-center gap-3 p-2 ">
                            <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/recycle-bin.svg') }}" alt="Icon"></div>
                            <p class="md:block nav_text blue_text font-medium hidden">Deleted</p>
                        </li>
                    </a>
                    <a href="{{ route('upgrade')}}" class=" block w-full menu-item rounded-lg {{ Request::routeIs('upgrade') ? 'bg-blue-100' : '' }}">
                        <li class=" flex justify-start items-center gap-3 p-2 ">
                            <div class="w-7"><img class="filter-blue w-7" src="{{ asset('logo_icons/up.svg') }}" alt="Icon"></div>
                            <p class="md:block nav_text blue_text font-medium hidden">Upgrade</p>
                        </li>
                    </a>
                </ul>
            </div>

            <form action=" {{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button id="sidebar-signout" class=" flex justify-start items-center gap-3 w-full p-2 cursor-pointer">
                    <img class="filter-red max-w-6" src="{{ asset('logo_icons/sign-out-alt.svg') }}" alt="Sign out">
                    <p class="md:block nav_text red_text font-medium hidden">Logout</p>
                </button>
            </form>
        </nav>
        
        <div class="md:pl-52 pl-14 pt-12 content here  w-full h-full">
            {{ $slot }}
        </div>

</body>
</html>
      