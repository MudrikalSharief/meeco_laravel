<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Verify Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .verification-input::-webkit-outer-spin-button,
        .verification-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .verification-input {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-gray-100 px-3 flex items-center justify-center min-h-screen">

    <div class="fixed right-0 top-0 pt-3 pr-3">
      <a href="{{ route('landing')}}"><img class="w-5" src="{{ asset('logo_icons/x.svg')}}" alt=""></a>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-xl font-bold text-blue-500 text-center mb-4">Verify Your Account</h1>
        
        <p class="text-center text-gray-600 mb-3">
            Enter the 6-digit verification code sent to<br>
            <span class="font-semibold">{{ isset($email) ? $email : 'your email' }}</span>
        </p>
        
        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif
        
        @if(session('email_error'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative mb-4">
                {!! session('email_error') !!}
            </div>
        @endif
        
        <form action="{{ route('verify.email') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex justify-center space-x-2">
                @for ($i = 1; $i <= 6; $i++)
                <input 
                    type="number" 
                    name="code[]" 
                    id="code-{{ $i }}"
                    class="verification-input w-12 h-12 text-center text-lg font-bold border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    min="0"
                    max="9"
                    required
                    maxlength="1" 
                    autocomplete="off"
                    data-index="{{ $i }}"
                >
                @endfor
            </div>
            
            @error('verification_code')
                <p class="text-red-500 text-sm text-center">{{ $message }}</p>
            @enderror
            
            <div class="text-center">
                <button type="submit" class="bg-blue-500 w-full text-white py-2 px-4 rounded-md text-sm">
                    VERIFY ACCOUNT
                </button>
            </div>
        </form>
        
        <div class="text-center text-sm mt-4">
            <form action="{{ route('verify.resend') }}" method="POST">
                @csrf
                <p class="text-gray-600">
                    Didn't receive a code? 
                    <button type="submit" class="text-blue-500 underline">Resend</button>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.verification-input');
            
            inputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    // Ensure only one digit
                    if (this.value.length > 1) {
                        this.value = this.value.slice(0, 1);
                    }
                    
                    // Auto-focus next input when a digit is entered
                    const index = parseInt(this.dataset.index);
                    if (this.value !== '' && index < 6) {
                        const nextInput = document.getElementById(`code-${index + 1}`);
                        if (nextInput) nextInput.focus();
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    // Handle backspace to go to previous input
                    const index = parseInt(this.dataset.index);
                    if (e.key === 'Backspace' && this.value === '' && index > 1) {
                        const prevInput = document.getElementById(`code-${index - 1}`);
                        if (prevInput) prevInput.focus();
                    }
                });
            });
        });
    </script>
</body>
</html>
