<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'chname'
    ];
}
