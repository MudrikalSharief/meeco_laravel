<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    function getTwoFactorAuthState(){
        $auth_state = $this->selectRaw('tf-auth-state')->get();

        return $auth_state;
    }
}
