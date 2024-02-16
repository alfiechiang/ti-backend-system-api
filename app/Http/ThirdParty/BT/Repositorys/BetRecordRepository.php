<?php


namespace App\Http\ThirdParty\BT\Repositorys;

use App\Models\SlotReocrd;

class BetRecordRepository
{



    public function list($data)
    {

        $Builder = SlotReocrd::where('account', $data['account']);
        if (!empty($data['starttime']) && !empty($data['endtime'])) {

            $Builder = $Builder->where("open_score_time", ">=", $data['starttime'])
                ->where("open_score_time", "<=", $data['endtime']);
        }
        $data = $Builder->where("caculate", true)->where("type","BT") //ＢＴ電子機代號
        ->orderBy('created_at', 'desc')->paginate($data['per_page']);

        return $data;
    }


}
