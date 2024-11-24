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
}
