<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 px-3 flex items-center justify-center min-h-screen">

    
    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm w-full">
        <a href="{{ route('loggedin')}}">back</a>
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Create your profile</h1>
        
        <form action="{{ route('register') }}" class="space-y-4" method="POST">
            @csrf

          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" value="{{ old('name')}}" 
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
          </div>
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="text" name="email" id="email" placeholder="Enter your email" value="{{ old('email')}}" 
            class="  mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
          </div>
          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
          <!-- Confirm Password Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>
          <!-- Submit Button -->
          <button type="submit" class=" bg-blue-500  w-full text-white py-2 rounded-md ">CREATE ACCOUNT</button>
        </form>

      </div>

</body>
</html>