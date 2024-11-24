<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AUTHcontroller extends Controller
{   
    //REgister Usesr
    public function register_user(Request $request){
        //Validate
        $field = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:3', 'confirmed']
        ]);

        //Register
        $user = User::create($field);

        //Login
        Auth::login($user);

        //Redirect
        return redirect()->route('loggedin');
    }

    //login user
    public function login_user(Request $request){
        //Validate
        $field = $request->validate([
            'email' => ['required', 'max:255','email'],
            'password' => ['required']
        ]);

        //Try login
        if(Auth::attempt($field,$request->remember)){
            return redirect()->route('capture');
            
        }else{
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
