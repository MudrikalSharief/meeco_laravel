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
        'duration',
        'start_date',
        'end_date',
        'status',
        'photo_to_text',
        'reviewer_generator',
        'mock_quiz_generator',
        'save_reviewer',
        'save_reviewer_limit',
        'perks',
    ];

    protected $primaryKey = 'promo_id'; 
}
