<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerCoinEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'coin_name',
        'coin_daily',
        'coin',
        'stop'
    ];
}
