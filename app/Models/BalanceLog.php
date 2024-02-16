<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'out_account',
        'out_name',
        'in_account',
        'in_name',
        'balance',
        'balance_log',
        'operator',
        'operator_time'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];
}
