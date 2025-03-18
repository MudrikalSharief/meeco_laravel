<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.js"></script>
    <title>{{ env('APP_NAME') }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Inter%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin_transactions.js', 'resources/js/admin_statistics.js', 'resources/js/admin_newstatistics.js'])
    @vite(['resources/css/admin-layout.css'])
    @vite(['resources/css/data-admin.css'])
    @vite(['resources/js/admin.js'])
    @vite(['resources/css/contact.css'])
    
</head>
<body class="h-screen w-full">

    {{-- Upper Navigation --}}
    <div id="upper_nav" class="fixed pl-3 z-10 pr-3 bg-gray-100 h-12 w-full flex items-center justify-end md:pl-64">
        <a href="{{ route('profile') }}" class="flex items-center">
            @auth('admin')
                <p class="name mr-1">{{ auth('admin')->user()->firstname }} {{ auth('admin')->user()->lastname }}</p>
            @endauth
            <div class="profile_holder w-9 h-9 rounded-full overflow-hidden border">
                <img class="w-full h-full object-contain" src="{{ asset('logo_icons/3.jpg') }}" alt="Profile">
            </div>
        </a>
    </div>

    
    <nav class="sidebar fixed h-full z-20">
        <div class="logo_burger_holder flex justify-between items-center w-full mb-4">
            <div class="logo_holder flex justify-start items-center gap-6 w-full md:w-auto">
                <img class="max-w-12" src="{{ asset('logo_icons/logo_head.png') }}" alt="Logo">
                <p class="md:block nav_text blue_text text-2xl font-bold hidden">meeco</p>
            </div>
            <div id="burger" class="burger_holder w-7 cursor-pointer md:hidden mx-auto">
                <img src="{{ asset('logo_icons/menu-burger.svg') }}" alt="Menu">
            </div>
        </div>

        <ul class="menu md:block hidden">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Users</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('admin.transactions') }}" class="menu-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Subscription</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('admin.newtransactions') }}" class="menu-item {{ Request::is('admin/newtransactions*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">New Subscription</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('admin.statistics') }}" class="menu-item {{ Request::is('admin/statistics*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Statistics</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('admin.newstatistics') }}" class="menu-item {{ Request::is('admin/newstatistics*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">New Statistics</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.subscription') }}" class="menu-item {{ Request::is('admin/subscription*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Promos</span>
                </a>
            </li>
            
            {{-- <li>
                <a href="{{ route('admin.account') }}" class="menu-item {{ request()->routeIs('admin.account') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Account</span>
                </a>
            </li> --}}
            {{-- <li>
                <a href="{{ route('admin.admin-manage') }}" class="menu-item {{ request()->routeIs('admin.admin-manage') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM12 14a4 4 0 100-8 4 4 0 000 8zm0 0c2.21 0 4 1.79 4 4v1H8v-1c0-2.21 1.79-4 4-4z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Manage Admin</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('admin.support') }}" class="menu-item {{ Request::is('admin/support*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Support Tickets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.logs') }}" class="menu-item {{ Request::is('admin/logs*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Logs</span>
                </a>
            </li>
        </ul>

        <ul class="menu bottom-menu">
            <li>
                <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Settings</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="nav-text md:inline-block hidden">Logout</span>
                </a>
                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Modal Sidebar -->
    <div id="modal_sidebar" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50" style="display: none;">
        <div class="sidebar bg-white h-full w-64 p-4">
            <div class="logo_burger_holder flex justify-between items-center w-full mb-4">
            <div class="logo_holder flex justify-start items-center gap-6 w-full md:w-auto">
                <img class="max-w-12" src="{{ asset('logo_icons/logo_head.png') }}" alt="Logo">
                <p class="md:block nav_text blue_text text-2xl font-bold hidden">meeco</p>
            </div>
            <div id="burger_modal" class="burger_holder w-7 cursor-pointer md:hidden ml-auto">
                <img src="{{ asset('logo_icons/menu-burger.svg') }}" alt="Menu">
            </div>
            </div>

            <ul class="menu md:block hidden">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="nav-text md:inline-block ">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions') }}" class="menu-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.statistics') }}" class="menu-item {{ Request::is('admin/statistics*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Statistics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subscription') }}" class="menu-item {{ Request::is('admin/subscription*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="nav-text md:inline-block ">Subscription</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.account') }}" class="menu-item {{ Request::is('admin/account*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Account</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.admin-manage') }}" class="menu-item {{ Request::is('admin/admin-manage*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM12 14a4 4 0 100-8 4 4 0 000 8zm0 0c2.21 0 4 1.79 4 4v1H8v-1c0-2.21 1.79-4 4-4z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Manage Admin</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.support') }}" class="menu-item {{ Request::is('admin/support*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Support Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.logs') }}" class="menu-item {{ Request::is('admin/logs*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="nav-text md:inline-block ">Logs</span>
                    </a>
                </li>
            </ul>

            <ul class="menu bottom-menu">
                <li>
                    <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="nav-text md:inline-block ">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="nav-text md:inline-block ">Logout</span>
                    </a>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="content pl-52 pt-12 md:pl-18 lg:pl-100">
        {{ $slot }}
    </div>
    <script>      
       document.getElementById('admin-logout-form').addEventListener('submit', function() {
            window.location.href = "{{ route('admin.login') }}";
        });
    </script>
</body>
</html>
