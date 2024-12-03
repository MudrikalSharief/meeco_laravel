<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raw extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow the convention
    protected $table = 'raw';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'raw_id';

    // Indicate if the IDs are auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable attributes
    protected $fillable = ['topic_id', 'raw_text'];

    // Define the relationship with the Topic model
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }
}