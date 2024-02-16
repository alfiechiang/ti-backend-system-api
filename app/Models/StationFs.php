<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationFs extends Model
{
    use HasFactory;

    protected $casts = [
        'total_bet' => 'decimal:2',
        'valid_bet' => 'decimal:2',
        'result' => 'decimal:2',
        'uplevel_occupy_money' => 'decimal:2',
        'occupy_money' => 'decimal:2',
        'water_money' => 'decimal:2'
    ];
}
