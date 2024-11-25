<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 px-5 flex items-center justify-center min-h-screen">

    <div class=" fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"><img class="w-5" src="{{ asset('logo_icons/x.svg')}}" alt=""></a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm min-w-52 w-full">
        
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Welcome Back</h1>
        
        <form action="{{ route('login') }}" class="space-y-4" method="POST">
            @csrf

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 ">Email</label>
            <input type="text" name="email" id="email" placeholder="Enter your email" value="{{ old('email')}}" 
            class="  mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" class=" text-sm mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

         {{-- CheckBox --}}

         <div class="mb-4 flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" class=" text-xs">Remember me</label>
        </div>

          <!-- Submit Button -->
          <button type="submit" class=" bg-blue-500  w-full text-white py-2 rounded-md text-sm ">Login</button>
        </form>
        <p class=" text-xs mt-2">Don’t have an Account yet? <a class=" text-blue-500" href="{{ route('register')}}">sign up</a>. </p> 
        @error('failed')
            <p class="error">{{ $message }}</p>
        @enderror
      </div>

</body>
</html>