<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-focus-effect:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        }
        .btn-hover-effect:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
        }
        .text-gradient {
            background: linear-gradient(to right, #ffffff, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .animate-text-reveal {
            animation: textReveal 1.2s ease-out forwards;
        }
        @keyframes textReveal {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <div class="fixed left-0 top-0 m-6 z-10">
      <a href="{{ route('landing')}}" class="rounded-full bg-white p-3 shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      </a>
    </div>

    <!-- Left side - Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-8 animate-fade-in">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-blue-400 to-blue-600"></div>
            
            <div class="flex justify-center mb-8">
                <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">Welcome Back</h1>
            <p class="text-gray-500 text-center mb-6 text-sm">Sign in to continue your journey</p>
            
            <form action="{{ route('login') }}" class="space-y-5" method="POST">
                @csrf

              <!-- Email Field -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="text" name="email" id="email" placeholder="Enter your email" value="{{ old('email')}}" 
                class="input-focus-effect mt-1 w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Password Field -->
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" 
                class="input-focus-effect text-sm mt-1 w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none transition-all duration-200">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

             {{-- CheckBox --}}
             <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <label for="remember" class="text-sm text-gray-600">Remember me</label>
              </div>
              <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition-all">Forgot Password?</a>
            </div>
              <!-- Submit Button -->
              <button type="submit" class="btn-hover-effect bg-blue-600 w-full text-white py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-200">Sign In</button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-sm text-center text-gray-600">Don't have an Account yet? 
                    <a class="text-blue-600 hover:text-blue-800 font-medium hover:underline" href="{{ route('register')}}">Sign Up</a>
                </p> 
                @error('failed')
                    <p class="text-red-500 text-xs text-center mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Right side - Image -->
    <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 items-center justify-center relative overflow-hidden animate-fade-in">
        <!-- Decorative circles -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
        <div class="absolute top-1/4 right-1/4 w-16 h-16 bg-white opacity-10 rounded-full"></div>
        
        <!-- Animated particles -->
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white rounded-full opacity-60 animate-ping"></div>
            <div class="absolute top-3/4 left-2/3 w-2 h-2 bg-white rounded-full opacity-60 animate-ping" style="animation-delay: 1s"></div>
            <div class="absolute top-1/3 left-1/2 w-2 h-2 bg-white rounded-full opacity-60 animate-ping" style="animation-delay: 2s"></div>
        </div>
        
        <!-- Abstract shapes -->
        <div class="absolute top-1/3 left-1/4 w-24 h-24 bg-blue-300 opacity-20 rounded-full blur-xl"></div>
        <div class="absolute bottom-1/3 right-1/3 w-32 h-32 bg-purple-300 opacity-20 rounded-full blur-xl"></div>
        
        <div class="z-10 flex flex-col items-center justify-center">
            <div class="animate-text-reveal">
                <h2 class="text-gradient text-5xl font-bold mb-2">Welcome to Meeco</h2>
                <p class="text-blue-100 text-lg mb-10 text-center">Organize, Connect, Discover</p>
            </div>
            <img src="{{asset('logo_icons/pictures/present.png')}}" alt="Info Digest Mascot" 
                 class="max-w-full h-auto rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-500 border-4 border-white/20">
        </div>
    </div>
</body>
</html>