<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LotteryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'account',
        'type',
        'period',
        'stake_id',
        'stake_name',
        "odds",
        "bet_money",
        "status",
        "result",
        "bet_time"
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];




}
