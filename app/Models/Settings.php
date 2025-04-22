<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Settings extends Model
{

    protected $fillable = ['tf_auth_state'];

    static function update2FactorAuthState($state){

        if($state){
            
            $currentState = $state->tf_auth_state;

            $newState = ($currentState === 'on') ? 'off' : 'on';
          
            $state->tf_auth_state = $newState;
            $state->save(); 

        }

        return $newState;
    }

    static function fetch2FactorAuthState(){

           $state = Settings::first();

           return $state;

    }

    static function fetchPasswordConfigurations(){

        $configurations = Settings::select('user_min_char', 'admin_min_char', 'user_spec_char', 'admin_spec_char', 'user_min_num', 'admin_min_num')->get();
        
        return $configurations;

    }
}
