<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    use HasFactory;

    protected $table = 'admin_actions';
    protected $primaryKey = 'action_id';

    protected $fillable = [
        'action_type',
        'timestamp',
        'details',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}
