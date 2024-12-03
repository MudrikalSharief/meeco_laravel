<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow the convention
    protected $table = 'subjects';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'subject_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = [
        'user_id', 
        'name'
    ];


    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function topic()
    {
        return $this->hasMany(Topic::class, 'subject_id', 'subject_id');
    }
}