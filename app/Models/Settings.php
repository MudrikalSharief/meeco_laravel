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

            if($currentState === 'on'){
                $newState = 'off';
            }else{
                $newState = 'on';
            }
          

            Log::info($newState);

            $state->tf_auth_state = $newState;
            $state->save(); 

        }

        return $newState;
    }

    static function fetch2FactorAuthState(){

           $state = Settings::first();

           return $state;

    }
}
