<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us';

    protected $fillable = [
        'ticket_reference',
        'email',
        'category',
        'subject',
        'question',
        'upload',
        'status',
        'date_created',
        'last_post'
    ];

    protected $casts = [
        'upload' => 'array',
        'date_created' => 'datetime',
        'last_post' => 'datetime'
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class, 'ticket_id', 'ticket_id');
    }

    public function adminReplies()
    {
        return $this->hasMany(AdminReply::class, 'ticket_id', 'ticket_id');
    }
}
