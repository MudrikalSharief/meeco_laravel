<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'name', 'price', 'perks', 'duration', 'features', 'start_date', 'end_date', 'discount_type', 'percent_discount', 'status', 'photo_to_text', 'photo_limit', 'reviewer_generator', 'reviewer_limit', 'mock_quiz_generator', 'mock_quiz_limit', 'save_reviewer', 'save_reviewer_limit',
    ];

   
    protected $primaryKey = 'promo_id'; 
}
