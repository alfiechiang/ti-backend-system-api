<?php

namespace App\Http\Repositorys;

use App\Models\Hierarchy;
use App\Models\Notify;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class NotifyRepository
{

    public  function list()
    {
        # code...
        try {
            $data = Notify::all();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public  function update($data)
    {
        # code...
        try {
            $notify = Notify::find(1);
            if (is_null($notify)) {
                Notify::create($data);
            } else {
                $notify->starttime = $data['starttime'];
                $notify->endtime = $data['endtime'];
                $notify->open = $data['open'];
                $notify->content = $data['content'];
                $notify->save();
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
