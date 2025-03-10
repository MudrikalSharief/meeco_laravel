<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'price',
        'duration',
        'start_date',
        'end_date',
        'status',
        'perks',
    ];

    protected $primaryKey = 'promo_id'; 
}
