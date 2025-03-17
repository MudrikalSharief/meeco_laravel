<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
        }
        
        .show-modal {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 px-5 flex items-center justify-center min-h-screen">

    <div class="fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"></a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm min-w-52 w-full">
        
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Forgot Password</h1>
        <p class="text-sm text-gray-600 text-center mb-6">Enter your email address and we'll send you a link to reset your password.</p>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        
        <form action="{{ route('password.email') }}" class="space-y-4" method="POST">
            @csrf

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email')}}" 
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Submit Button -->
          <button type="submit" class="bg-blue-500 w-full text-white py-2 rounded-md text-sm">Send Reset Link</button>
        </form>
        
        <p class="text-xs mt-4 text-center">Remember your password? <a class="text-blue-500" href="{{ route('login')}}">Login</a></p>
    </div>

    <!-- Modal for successful password reset email -->
    <div id="resetEmailModal" class="modal {{ session('status') ? 'show-modal' : '' }}">
        <div class="modal-content">
            <h2 class="text-lg font-semibold text-blue-500 mb-4">Email Sent</h2>
            <p class="mb-6">{{ session('status') }}</p>
            <a href="{{ route('login') }}" class="bg-blue-500 text-white py-2 px-4 rounded-md text-sm inline-block">Return to Login</a>
        </div>
    </div>

    @if(session('status'))
    <script>
        document.getElementById('resetEmailModal').classList.add('show-modal');
    </script>
    @endif

</body>
</html>
