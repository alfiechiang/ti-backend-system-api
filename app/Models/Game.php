<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_type',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
