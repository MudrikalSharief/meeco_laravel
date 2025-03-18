<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AUTHcontroller extends Controller
{   
    //Register Usesr
    public function register_user(Request $request){
        //Validate
        $field = $request->validate([
            'firstname' => ['required', 'max:255'],
            'middlename' => ['nullable', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/', 'confirmed']
        ]);

        //Register
        $user = User::create($field);

        // Login
        Auth::login($user);

        // Get the authenticated user
        $user = Auth::user();

        // Find the 'FreeTrial' promo
        $promo = Promo::where('name', 'Free Trial')->first();

        // Check if the promo exists
        if($promo){
            // Create a subscription for the user
            Subscription::create([
                'user_id' => $user->user_id,
                'promo_id' => $promo->promo_id,
                'reference_number' => 'Free Trial Promo ' . $user->user_id,
                'reviewer_created' => 0,
                'quiz_created' => 0,
                'status' => 'active',
                'subscription_type' => 'Admin Granted',
                'start_date' => now(),
                'end_date' => now()->addDays($promo->duration),
            ]);

                
        }else{
            return response()->json([
                'success' => false,
                'message' => 'FreeTrial Promo not found.',
                'user_id' => $user->user_id,
                'promo_id' => $promo->promo_id,
                'duration' => $promo->duration,
            ]);
        }

            //Redirect
            if($promo && $user){
                return redirect()->route('capture');
            }else{
                return back()->withErrors([
                    'failed' => 'Account Doesnt Exist.'
                ]);
            }

    }

    //login user
    public function login_user(Request $request)
    {
        // Validate
        $field = $request->validate([
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required']
        ]);
    
        // Try login
        if (Auth::attempt($field, $request->remember)) {
            $user = Auth::user();
    
            // Check if the user has an active subscription
            $subscription = Subscription::where('user_id', $user->user_id)
                ->whereIn('status', ['Active', 'Limit Reached'])
                ->with('promo')
                ->first();
            
            // Check if the user has an active subscription
            if ($subscription) {

                // // Check if the subscription's reviewer and quiz limits have been reached
                // if ($subscription->promo->reviewer_limit <= $subscription->reviewer_created && $subscription->promo->quiz_limit <= $subscription->quiz_created) {
                    
                //     // Update the subscription status to inactive
                //     $subscription->update(['status' => 'Limit Reached']);
                // }

                // Check if the subscription's end date and time have already passed the current date and time
                // Parse the end date
                $endDate = \Carbon\Carbon::parse($subscription->end_date);

                // Debugging: Log the end date and current date
                Log::info('Subscription end date: ' . $endDate);
                Log::info('Current date: ' . now());
                Log::info('Current timezone: ' . config('app.timezone'));

                // Check if the subscription's end date and time have already passed the current date and time
                if ($endDate->isPast()) {
                    // Update the subscription status to inactive
                    $subscription->update(['status' => 'Expired']);
                }
                
            }
            
            //remove all the image
            $user = $request->user();
            $userid = $user->user_id;
        
            $filePath = 'uploads/image' . $userid;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->deleteDirectory($filePath);
            }

            // Redirect to the capture route
            return redirect()->route('capture');
        } else {
            return back()->withErrors([
                'failed' => 'Account Doesnt Exist.'
            ]);
        }
    }

    //logout user
    public function logout_user(Request $request){

        //remove all the image
        $user = $request->user();
        $userid = $user->user_id;
    
        $filePath = 'uploads/image' . $userid;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->deleteDirectory($filePath);
        }


       //logout the user
        Auth::logout();

        //invalidate the session
       $request->session()->invalidate();

       //regenerates the token
        $request->session()->regenerateToken();

        //redirect the users
        return redirect('login');
    }

    /**
     * Store registration data in session and redirect to verification page
     */
    public function storeRegistration(Request $request)
    {
        // Validate user input
        $validatedData = $request->validate([
            'firstname' => ['required', 'max:255'],
            'middlename' => ['nullable', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/', 'confirmed']
        ]);

        // Store registration data in session
        Session::put('registration_data', $validatedData);
        
        // Generate and send verification code
        $verificationCode = $this->generateVerificationCode();
        Session::put('verification_code', $verificationCode);
        
        // Send the verification code via email
        $this->sendVerificationEmail($validatedData['email'], $verificationCode);
        
        // Redirect to verification page
        return redirect()->route('verify.show');
    }

    /**
     * Show the verification form
     */
    public function showVerificationForm()
    {
        // Check if there's registration data in the session
        if (!Session::has('registration_data')) {
            return redirect()->route('register')->with('error', 'Please complete registration first.');
        }
        
        // Get the email for display
        $email = Session::get('registration_data')['email'];
        
        // Pass email to the view
        return view('auth.verify-user', ['email' => $email]);
    }

    /**
     * Verify the email with the provided code
     */
    public function verifyEmail(Request $request)
    {
        // Get the verification code from the request
        $inputCode = implode('', $request->input('code'));
        
        // Get the stored verification code from session
        $storedCode = Session::get('verification_code');
        
        // If codes don't match, redirect back with error
        if ($inputCode !== $storedCode) {
            return back()->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
        }
        
        // Get registration data from session
        $registrationData = Session::get('registration_data');
        
        // Create new user
        $user = User::create($registrationData);
        
        // Find the 'FreeTrial' promo
        $promo = Promo::where('name', 'Free Trial')->first();
        
        // Create a subscription for the user if promo exists
        if ($promo) {
            Subscription::create([
                'user_id' => $user->user_id,
                'promo_id' => $promo->promo_id,
                'reference_number' => 'Free Trial Promo ' . $user->user_id,
                'reviewer_created' => 0,
                'quiz_created' => 0,
                'status' => 'active',
                'subscription_type' => 'Admin Granted',
                'start_date' => now(),
                'end_date' => now()->addDays($promo->duration),
            ]);
        }
        
        // Clear session data
        Session::forget(['registration_data', 'verification_code']);
        
        // Login the user
        Auth::login($user);
        
        return redirect()->route('capture');
    }

    /**
     * Generate a random 6-digit verification code
     */
    private function generateVerificationCode()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send verification email using Mailgun
     */
    private function sendVerificationEmail($email, $code)
    {
        $data = [
            'email' => $email,
            'code' => $code,
            'name' => Session::get('registration_data')['firstname']
        ];
        
        try {
            Mail::send('emails.verification', $data, function($message) use ($email) {
                $message->to($email)
                        ->subject('Verify Your Account');
            });
            
            // Check if mail was sent successfully
            if (count(Mail::failures()) > 0) {
                // Store error message for display
                Session::flash('email_error', 'Could not send email. Your verification code is: ' . $code);
                Log::error('Failed to send email to ' . $email . '. Mail failures: ' . json_encode(Mail::failures()));
            }
        } catch (\Exception $e) {
            // Log the error and store the code to display to the user
            Log::error('Email sending failed: ' . $e->getMessage());
            Session::flash('email_error', 'Email could not be sent due to server configuration. Your verification code is: ' . $code);
        }
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode()
    {
        // Check if registration data exists in session
        if (!Session::has('registration_data')) {
            return redirect()->route('register')->with('error', 'Please complete registration first.');
        }
        
        $registrationData = Session::get('registration_data');
        
        // Generate new verification code
        $verificationCode = $this->generateVerificationCode();
        Session::put('verification_code', $verificationCode);
        
        // Send the verification code via email
        $this->sendVerificationEmail($registrationData['email'], $verificationCode);
        
        return back()->with('message', 'Verification code has been resent.');
    }
    
    /**
     * For testing purposes only - show current verification code
     * Remove this in production
     */
    public function showCurrentCode()
    {
        if (!Session::has('verification_code')) {
            return redirect()->route('register')->with('error', 'No verification code found.');
        }
        
        return response()->json([
            'code' => Session::get('verification_code'),
            'message' => 'This endpoint is for testing purposes only.'
        ]);
    }

    /**
     * Handle the forgot password request
     */
    public function forgotPassword(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if email exists in the users table
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email does not exist in our records.'
            ])->withInput();
        }

        // Generate a reset token
        $token = Str::random(60);
        
        // Store the token in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Generate the reset link
        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);
        
        // Send the reset link via email
        try {
            Mail::send('emails.reset-password', [
                'resetLink' => $resetLink,
                'name' => $user->firstname
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Reset Your Password');
            });
            
            // Check if mail was sent successfully
            // if (count(Mail::failures()) > 0) {
            //     Log::error('Failed to send password reset email to ' . $user->email . '. Mail failures: ' . json_encode(Mail::failures()));
            //     return back()->withErrors([
            //         'email' => 'Could not send reset link. Please try again later.'
            //     ])->withInput();
            // }
            
            return back()->with('status', 'Reset link has been sent to your email address.');
            
        } catch (\Exception $e) {
            Log::error('Password reset email sending failed: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Could not send reset link. Please try again later.'
            ])->withInput();
        }
    }

    /**
     * Handle the reset password request
     */
    public function resetPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/', 'confirmed'],
        ]);

        // Check if the token is valid
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors([
                'email' => 'Invalid or expired reset token.'
            ]);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email does not exist in our records.'
            ]);
        }

        // Update the password
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        // Delete the password reset record
        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        // Redirect to login page with success message
        return redirect()->route('login')->with('status', 'Password has been reset successfully!');
    }
}