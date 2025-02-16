<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Add this line to import the User model

class AUTHadminController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login-admin');
    }

    // Show Register Form
    public function showRegisterForm()
    {
        return view('auth.register-admin');
    }

    // Register Admin
    public function register_admin(Request $request){
        // Validate
        $field = $request->validate([
            'firstname' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:admins'],
            'password' => ['required', 'min:3', 'confirmed']
        ]);

        // Register
        $admin = Admin::create($field);

        // Login
        Auth::guard('admin')->login($admin);

        // Redirect
        return redirect()->route('admin.dashboard');
    }

    // Login Admin
    public function login_admin(Request $request){
        // Validate
        $field = $request->validate([
            'email' => ['required', 'max:255','email'],
            'password' => ['required']
        ]);

        // Try login
        if(Auth::guard('admin')->attempt($field, $request->remember)){
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors([
                'failed' => 'Admin Account Does not Exist.'
            ]);
        }
    }

    // Logout Admin
    public function logout_admin(Request $request){
        // Logout the admin
        Auth::guard('admin')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the token
        $request->session()->regenerateToken();

        // Redirect the admin
        return redirect('admin/login');
    }

    public function index(){
        $admins = Admin::all();
        return view('admin.admin_manage', compact('admins'));
    }

    // Show Users
    public function showUsers()
    {
        $users = User::paginate(10);
        return view('admin.admin_users', compact('users'));
    }

    // Get User by ID
    public function getUserById($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_detail', compact('user'));
    }

    // Create User
    public function createUser(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed'
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    // Delete User by Email
    public function deleteUserByEmail($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}