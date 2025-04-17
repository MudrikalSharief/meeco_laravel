<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function toggleTwoFactorAuthState(){
        $auth_state = Settings::getTwoFactorAuthState();
        
        if($auth_state === 'off'){
            $auth_state = 'on';
        }else{
            $auth_state = 'off';
        }

        return res()->json([
            'auth_state'=>$auth_state
        ]);
    }
}
