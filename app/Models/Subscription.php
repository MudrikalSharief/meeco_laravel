<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'reference_number', 'name', 'pricing', 'duration', 'start_date', 'end_date', 'status', 'promo_id', 'user_id'
    ];
    

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function promo(){
        return $this->belongsTo(Promo::class, 'promo_id', 'promo_id');
    }
}
