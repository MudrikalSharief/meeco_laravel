<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Settings extends Model
{

    protected $fillable = ['tf_auth_state'];

    static function updateTwoFactorAuthState(){
        $state = Settings::first();


        if($state){
            $currentState = $state->tf_auth_state;

            $newState = ($currentState === 'on') ? 'off' : 'on';

            $state->tf_auth_state = $newState;
            $state->save(); 

        }


        return $state;
    }
}
