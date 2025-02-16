<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promos';

    protected $primaryKey = 'promo_id';

    protected $fillable = [
        'name', 'price', 'perks', 'duration', 'features', 'limitations',
        'start_date', 'end_date', 'discount_type', 'percent_discount', 'status'
    ];
}
