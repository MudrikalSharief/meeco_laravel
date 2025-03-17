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

        /* Red circle with grey outline */
        #lottie-container .notification-indicator {
                display: none; /* Initially hidden */
                content: '';
                position: absolute;
                z-index: 1;
                top: 15%;
                right: 15%;
                width: 30%;
                height: 30%;
                border-radius: 50%;
                background-color: rgb(230, 16, 16);
            }


            /* Notification box styling */
        #notification-box {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: 16rem;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #notification-box.show {
            display: block;
        }

    </style>
</head>
<body class="relative min-h-screen flex flex-col bg_base_lightmode">

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
    
    <div class=" pt-1 w-full  mb-20 mt-12 flex justify-center items-center">
        <div class="max-w-lg w-full">
            {{ $slot ?? '' }}
        </div>
    </div>
    
    <script>
        var notificationIndicator = document.querySelector('#lottie-container .notification-indicator');
          
        
        document.getElementById('lottie-container').addEventListener('click', function() {
            var notificationBox = document.getElementById('notification-box');
            notificationBox.classList.toggle('show');
            
    });

    
    // Close the notification box when clicking outside of it
    document.addEventListener('click', function(event) {
        var notificationBox = document.getElementById('notification-box');
        var lottieContainer = document.getElementById('lottie-container');
        if (!lottieContainer.contains(event.target) && !notificationBox.contains(event.target)) {
            notificationBox.classList.remove('show');
            }
   
        });

        function checkNotifications(hasNotifications = false) {

            var notificationIndicator = document.querySelector('#lottie-container .notification-indicator');

            if (hasNotifications) {
                if (!notificationIndicator) {
                    notificationIndicator = document.createElement('div');
                    notificationIndicator.className = 'notification-indicator';
                    document.getElementById('lottie-container').appendChild(notificationIndicator);
                }
                notificationIndicator.style.display = 'block';
            } else {
                if (notificationIndicator) {
                    notificationIndicator.style.display = 'none';
                }
            }
        }
        

        
        // Function to check subscription status
       function checkSubscriptionStatus() {
           fetch('/subscription/check', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
               },
               body: JSON.stringify({})
           })
           .then(response => response.json())
           .then(data => {
               if (data.success) {
                   var notificationBoxContent = document.querySelector('#notification-box .p-4');
                   if (data.isCloseToExpiring) {
                            
                        checkNotifications(true);
                       // Add the message to the notification box
                       notificationBoxContent.innerHTML = data.isCloseToExpiringMessage;
                       notificationBoxContent.classList.add('bg-blue-300');
                       
                   }else if (data.recentlyExpiredMessage) {
                        checkNotifications(true);
                       // Add the message to the notification box
                       notificationBoxContent.innerHTML = data.recentlyExpiredMessage;
                       notificationBoxContent.classList.add('bg-red-300');

                    } else {
                        checkNotifications();
                        // Reset the notification box content
                        notificationBoxContent.innerHTML = '<p class="text-gray-700">No new notifications</p>';
                    }
                    
                    
                } else {
                   alert(data.message);
               }
           })
           .catch(error => console.error('Error checking subscription status:', error));
       }

       // Call the function to check subscription status when the page loads
       document.addEventListener('DOMContentLoaded', function() {
           checkSubscriptionStatus();
       });
        
       
    </script>
</body>
</html>
