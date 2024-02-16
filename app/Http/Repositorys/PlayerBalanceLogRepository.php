<?php

namespace App\Http\Repositorys;

use App\Models\PlayerBalanceLog;
use Exception;
use Illuminate\Support\Facades\Log;




class PlayerBalanceLogRepository
{


    public function create($account, $category,$balance_before ,$balance_after )
    {


        try {

            PlayerBalanceLog::create([
                'account' => $account,
                'change_time' => date('Y-m-d H:i:s'),
                'game_category' => $category,
                'balance_before' => $balance_before,
                'expense' => $balance_after - $balance_before,
                'balance_after' => $balance_after

            ]);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
