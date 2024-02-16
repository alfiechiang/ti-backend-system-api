<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberGameStatus extends Model
{
    use HasFactory,BaseModel;

    protected $fillable = [
        'game_type',
        'game_name',
        'member_id',
        'freeze_status',
        'has_test_account'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
