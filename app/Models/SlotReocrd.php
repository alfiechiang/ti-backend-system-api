<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotReocrd extends Model
{
    use HasFactory, BaseModel;
    protected $fillable = [
        'account',
        'open_score_time',
        'wash_score_time',
        'machine_model',
        'machine_name',
        'open_score',
        'wash_score',
        'result',
        'caculate',
        'type'
    ];
}
