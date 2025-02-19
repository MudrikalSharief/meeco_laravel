<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fill_in_the_blank extends Model
{
    
    // Specify the table name if it doesn't follow the convention
    protected $table = 'fill_in_the_blank';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'fill_in_the_blank_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'fill_in_the_blank_id', 
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
