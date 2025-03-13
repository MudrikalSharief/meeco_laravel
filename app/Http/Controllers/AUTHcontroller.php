<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
            'password' => ['required', 'min:3', 'confirmed']
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
                'reference_number' => 'Free Trial Promo',
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
                ->where('status', 'active')
                ->with('promo')
                ->first();
    
            if ($subscription) {
                // Check if the subscription's end date and time have already passed the current date and time
                if ($subscription->end_date < now()) {
                    // Update the subscription status to inactive
                    $subscription->update(['status' => 'expired']);
                }
    
                // Check if the subscription's reviewer and quiz limits have been reached
                if ($subscription->promo->reviewer_limit <= $subscription->reviewer_created && $subscription->promo->quiz_limit <= $subscription->quiz_created) {
                    // Update the subscription status to inactive
                    $subscription->update(['status' => 'limit']);
                }
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
       //logout the user
        Auth::logout();

        //invalidate the session
       $request->session()->invalidate();

       //regenerates the token
        $request->session()->regenerateToken();

        //redirect the users

        return redirect('login');
    }
}
