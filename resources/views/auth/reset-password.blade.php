<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 px-5 flex items-center justify-center min-h-screen">

    <div class="fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"></a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm min-w-52 w-full">
        
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Reset Password</h1>
        <p class="text-sm text-gray-600 text-center mb-6">Please enter your new password below.</p>
        
        <form action="{{ route('password.update') }}" class="space-y-4" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            <input type="hidden" name="email" value="{{ request()->query('email') }}">

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your new password" 
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your new password" 
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Submit Button -->
          <button type="submit" class="bg-blue-500 w-full text-white py-2 rounded-md text-sm">Reset Password</button>
        </form>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
    </div>

</body>
</html>
