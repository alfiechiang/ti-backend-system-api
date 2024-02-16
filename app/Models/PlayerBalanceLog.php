<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PlayerBalanceLog extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'account',
        'change_time',
        'game_category',
        'balance_before',
        'expense',
        'balance_after'
    ];

    function game_category()
    {



        $game_category = "";
        switch ($this->game_category) {

            case "lottery":
                $game_category = "彩票";
                break;
            case "slot":
                $game_category = "實機";
                break;
            case "slot_bt":
                $game_category = "BT電子";
                break;
        }
     
        return $game_category;
    }
}
