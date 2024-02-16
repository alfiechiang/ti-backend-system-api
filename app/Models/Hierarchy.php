<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hierarchy extends Model
{
    use HasFactory, BaseModel;
    protected $fillable = [
        'id',
        'account',
        'name',
        'coin_type',
        'commercial_mode',
        'level',
        'desc',
        'root_parent',
        'parent',
        'balance',
        'balance_desc',
        'occupy_percent',
        'occupy_desc',
        'water_occupy',
        'water_percent',
        'water_desc',
        'last_login',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'occupy_percent' => 'decimal:2',
        'water_occupy' => 'decimal:2',
        'water_percent' => 'decimal:2'
    ];

    public function upLevel()
    {
        return $this->hasOne(Hierarchy::class, 'id', 'parent');
    }

    public function coin()
    {
        return $this->hasOne(Coin::class, 'id', 'coin_type');
    }

    public function commercial()
    {
        return $this->hasOne(Commercial::class, 'id', 'commercial_mode');
    }
}
