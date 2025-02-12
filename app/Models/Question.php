<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
 use HasFactory;

    // Specify the table name if it doesn't follow the convention
    protected $table = 'questions';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'question_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'question_id', 
        'topic_id',
        'question_text',
    ];

    // Define the relationship with the User model
    
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }

    public function multiple_choice()
    {
        return $this->hasOne(multiple_choice::class,'question_id','question_id');
    }

}
