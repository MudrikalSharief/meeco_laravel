<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    use HasFactory;
    // Specify the table name if it doesn't follow the convention
    protected $table = 'Identification';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'Identification_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'Identification_id', 
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
