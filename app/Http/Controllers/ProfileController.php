<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->user_id)
                    ->where('status', 'active')
                    ->with('promo')
                    ->first();

        return view('components.profile', compact('user', 'subscription'));
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Delete the old profile picture if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = $path;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile picture updated successfully.');
    }
}
