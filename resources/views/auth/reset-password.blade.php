<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .password-requirements {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .requirement-met {
            color: #10b981;
        }
        .requirement-not-met {
            color: #ef4444;
        }
    </style>
</head>
<body class="bg-gray-100 px-5 flex items-center justify-center min-h-screen">

    <div class="fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"><img class="w-5" src="{{ asset('logo_icons/x.svg')}}" alt=""></a>
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
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
            oninput="checkPasswordStrength(this.value)">
            <div class="password-requirements mt-2">
                <div id="length-requirement" class="requirement-not-met">• Minimum 8 characters</div>
                <div id="lowercase-requirement" class="requirement-not-met">• At least one lowercase letter</div>
                <div id="uppercase-requirement" class="requirement-not-met">• At least one uppercase letter</div>
                <div id="number-requirement" class="requirement-not-met">• At least one number</div>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your new password" 
            class="mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
            oninput="checkPasswordMatch()">
            <div id="password-match" class="text-xs mt-1 hidden text-red-500">Passwords don't match</div>
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

    <script>
        function checkPasswordStrength(password) {
            // Check length
            const lengthRequirement = document.getElementById('length-requirement');
            if (password.length >= 8) {
                lengthRequirement.className = 'requirement-met';
            } else {
                lengthRequirement.className = 'requirement-not-met';
            }
            
            // Check lowercase
            const lowercaseRequirement = document.getElementById('lowercase-requirement');
            if (/[a-z]/.test(password)) {
                lowercaseRequirement.className = 'requirement-met';
            } else {
                lowercaseRequirement.className = 'requirement-not-met';
            }
            
            // Check uppercase
            const uppercaseRequirement = document.getElementById('uppercase-requirement');
            if (/[A-Z]/.test(password)) {
                uppercaseRequirement.className = 'requirement-met';
            } else {
                uppercaseRequirement.className = 'requirement-not-met';
            }
            
            // Check number
            const numberRequirement = document.getElementById('number-requirement');
            if (/[0-9]/.test(password)) {
                numberRequirement.className = 'requirement-met';
            } else {
                numberRequirement.className = 'requirement-not-met';
            }
            
            // Also check match if confirmation field has value
            if (document.getElementById('password_confirmation').value) {
                checkPasswordMatch();
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchMessage = document.getElementById('password-match');
            
            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchMessage.className = 'text-xs mt-1 text-green-500';
                    matchMessage.textContent = 'Passwords match';
                    matchMessage.classList.remove('hidden');
                } else {
                    matchMessage.className = 'text-xs mt-1 text-red-500';
                    matchMessage.textContent = "Passwords don't match";
                    matchMessage.classList.remove('hidden');
                }
            } else {
                matchMessage.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
