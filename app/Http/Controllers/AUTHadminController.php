<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Add this line to import the User model
use Illuminate\Support\Facades\DB;

use App\Models\ContactUs; // Add this line to import the ContactUs model
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
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }
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
        return redirect('admin');
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
            'middlename' => 'nullable|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed'
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        /* $admin_id = DB::table('sessions')->pluck('user_id');    

        DB::table('admin_actions')->insert([
            'action_id'=>rand(100000, 999999),
            'admin_id' => $admin_id,
            'action_type' => "Admin has added User". $user->id,
        ]); */

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    // Create Admin
    public function createAdmin(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:3|confirmed'
        ]);

        Admin::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('admin.admin-manage')->with('success', 'Admin created successfully.');
    }

    // Update Admin
    public function updateAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,admin_id',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:admins,email,' . $request->admin_id . ',admin_id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $admin->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
        ]);

        // Check if the logged-in admin is updating their own credentials
        if (Auth::guard('admin')->id() == $admin->admin_id) {
            // Logout the admin
            Auth::guard('admin')->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the token
            $request->session()->regenerateToken();

            // Redirect to admin login
            return redirect()->route('admin.login')->with('success', 'Admin updated successfully. Please log in again.');
        }

        return redirect()->route('admin.admin-manage')->with('success', 'Admin updated successfully.');
    }

    // Fetch Admin Data for Editing
    public function editAdmin($admin_id)
    {
        $admin = Admin::findOrFail($admin_id);
        return response()->json($admin);
    }

    // Delete Admin
    public function deleteAdmin(Request $request)
    {
        $admin = Admin::findOrFail($request->admin_id);
        $admin->delete();

        // Check if the logged-in admin is deleting their own account
        if (Auth::guard('admin')->id() == $admin->admin_id) {
            // Logout the admin
            Auth::guard('admin')->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the token
            $request->session()->regenerateToken();

            // Redirect to admin login
            return redirect()->route('admin.login')->with('success', 'Admin deleted successfully. Please log in again.');
        }

        return redirect()->route('admin.admin-manage')->with('success', 'Admin deleted successfully.');
    }

    // Delete User by Email
    public function deleteUser(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,user_id',
            'firstname' => 'required|max:255',
            'middlename' => 'nullable|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id . ',user_id',
        ]);

        $user = User::findOrFail($request->id);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'email' => $request->email,
            ]);
        });

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }
}