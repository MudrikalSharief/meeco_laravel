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
<body class="bg-gray-100 px-3 flex items-center justify-center min-h-screen">

    <div class=" fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"><img class="w-5" src="{{ asset('logo_icons/x.svg')}}" alt=""></a>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm w-full">
    
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Create your profile</h1>
        
        <form action="{{ route('register.store') }}" class="space-y-4" method="POST">
            @csrf

              <!-- Firstname Field -->
              <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" name="firstname" id="firstname" placeholder="Enter your firstname" value="{{ old('firstname')}}" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200">
                @error('firstname')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
              
              <!-- Middlename Field -->
              <div>
                <label for="middlename" class="block text-sm font-medium text-gray-700 mb-1">Middle Name (optional)</label>
                <input type="text" name="middlename" id="middlename" placeholder="Enter your middlename" value="{{ old('middlename')}}" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200">
                @error('middlename')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
              
              <!-- Lastname Field -->
              <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="lastname" id="lastname" placeholder="Enter your lastname" value="{{ old('lastname')}}" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200">
                @error('lastname')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
              
              <!-- Email Field -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="text" name="email" id="email" placeholder="Enter your email" value="{{ old('email')}}" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
              
              <!-- Password Field -->
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200"
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
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" 
                class="input-focus-effect mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none text-sm transition-all duration-200"
                oninput="checkPasswordMatch()">
                <div id="password-match" class="text-xs mt-1 hidden text-red-500">Passwords don't match</div>
              </div>

              <!-- Submit Button -->
              <button type="submit" id="submit-button" class="btn-hover-effect bg-blue-600 w-full text-white py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-200 mt-2">CREATE ACCOUNT</button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-sm text-center text-gray-600">Already have an account? 
                    <a class="text-blue-600 hover:text-blue-800 font-medium hover:underline" href="{{ route('login')}}">Log In</a>
                </p>
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
        <div class="absolute bottom-1/3 right-1/3 w-32 h-32 bg-indigo-300 opacity-20 rounded-full blur-xl"></div>
        
        <div class="z-10 flex flex-col items-center justify-center w-full">
            <div class="animate-text-reveal text-center w-full px-4">
                <h2 class="text-gradient text-4xl sm:text-5xl font-bold mb-2">Welcome to Meeco</h2>
                <p class="text-blue-100 text-lg mb-10">Your Digital Knowledge Companion</p>
            </div>
            <img src="{{asset('logo_icons/pictures/present.png')}}" alt="Info Digest Mascot" 
                 class="max-w-full h-auto rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-500 border-4 border-white/20">
        </div>
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