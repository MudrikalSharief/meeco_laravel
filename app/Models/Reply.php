<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $table = 'replies';

    protected $fillable = [
        'ticket_id',
        'reply_user_question',
        'reply_user_upload'
    ];

    protected $casts = [
        'reply_user_upload' => 'array'
    ];
}
