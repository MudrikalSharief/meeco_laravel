<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class true_or_false extends Model
{
    use HasFactory;
    // Specify the table name if it doesn't follow the convention
    protected $table = 'true_or_false';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'true_or_false_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'true_or_false_id', 
        'question_id',
        'question_text',
        'answer',
        'user_answer',
    ];

    // Define the relationship with the User model
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }


}
