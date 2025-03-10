<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'price',
        'perks',
        'duration',
        'start_date',
        'end_date',
        'image_limit',
        'reviewer_limit',
        'staquiz_limittus',
        'quiz_questions_limit',
        'can_mix_quiz',
        'mix_quiz_limit',
        'status',
    ];

    protected $primaryKey = 'promo_id'; 
}
