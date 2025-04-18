<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function toggleTwoFactorAuthState(){
        $auth_state = Settings::updateTwoFactorAuthState();
    

        return response()->json([
            'auth_state'=>$auth_state
        ]);
    }
}
