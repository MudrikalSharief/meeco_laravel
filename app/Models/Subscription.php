<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $primaryKey = 'subscription_id';
    
    protected $fillable = [
        'user_id',
        'promo_id',
        'reference_number',
        'duration',
        'start_date', 
        'end_date', 
        'status',
        'subcription_type',
    ];
    

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function promo(){
        return $this->belongsTo(Promo::class, 'promo_id', 'promo_id');
    }
}
