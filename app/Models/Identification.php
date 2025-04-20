<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    use HasFactory;

    protected $primaryKey = 'Identification_id';
    protected $table = 'Identification';

    protected $fillable = [
        'question_id',
        'question_text',
        'answer',
        'blooms_level',
        'user_answer',
    ];
}
