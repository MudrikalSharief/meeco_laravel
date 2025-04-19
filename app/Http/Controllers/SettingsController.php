<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller{

    function toggle2FactorAuthState(){

        $auth_state = Settings::fetch2FactorAuthState();
        $new_state = Settings::update2FactorAuthState($auth_state);

    }

    function get2FactorAuthState(){

        $auth_state = Settings::fetch2FactorAuthState();

        return response()->json([
            'auth_state'=> $auth_state
        ]);

    }

}


