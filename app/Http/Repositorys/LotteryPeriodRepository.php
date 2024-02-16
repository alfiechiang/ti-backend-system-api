<?php

namespace App\Http\Repositorys;

use App\Models\Hierarchy;
use App\Models\LotteryPeriod;
use App\Models\Notify;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class LotteryPeriodRepository
{

    public  function list($data)
    {
        # code...
        try {
            $Builder = new LotteryPeriod();
            if (!empty($data['period'])) {
                $Builder =  $Builder->where('period', $data['period']);
            }
            $data =  $Builder->paginate($data['per_page']);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
