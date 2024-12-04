<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow the convention
    protected $table = 'topics';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'topic_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = ['subject_id', 'name'];

    // Define the relationship with the User model
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function raws()
    {
        return $this->hasMany(Raw::class, 'topic_id', 'topic_id');
    }

    public function reviewers()
    {
        return $this->hasMany(Reviewer::class, 'topic_id', 'topic_id');
    }
}