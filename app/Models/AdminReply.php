<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    use HasFactory;

    protected $table = 'admin_replies';

    protected $fillable = [
        'ticket_id',
        'reply_admin_question',
        'reply_admin_upload',
    ];

    public function ticket()
    {
        return $this->belongsTo(ContactUs::class, 'ticket_id', 'ticket_id');
    }
}
