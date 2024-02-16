<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentRepository
{



    public  function list($per_page)
    {
        try {

            $data = Hierarchy::with("upLevel")
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::AGENT_LEVEL)->paginate($per_page);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function searchList($per_page, $account)
    {
        $user = Auth::user();

        if ($user->account == Constant::SUPERVISOR) {
            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::AGENT_LEVEL)
                ->where("account", $account)->paginate($per_page);
        } else {

            $hier = Hierarchy::where("account", $user->account)->first();
            $parent = $hier->id;
            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::AGENT_LEVEL)
                ->where("path", 'like', '%' . $parent . '%')
                ->where("account", $account)
                ->paginate($per_page);
        }

        return $data;
    }

    public function companyUnderAgentList($per_page)
    {
        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $station_ids = Hierarchy::where("level", Constant::STATION_LEVEL)->where("parent", $hier->id)->pluck("id");
        $data =  Hierarchy::with("upLevel")
            ->with("coin")
            ->with("commercial")
            ->where("level", Constant::AGENT_LEVEL)->whereIn("parent", $station_ids)
            ->paginate($per_page);
        return $data;
    }

    public  function stationUnderAgentList($per_page)
    {

        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $parent = $hier->id;

        try {
            $data = Hierarchy::with("upLevel")
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::AGENT_LEVEL)
                ->where("parent", $parent)->paginate($per_page);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public  function create($data)
    {
        # code...
        try {
            $collection = Hierarchy::create($data);
            $parent_collection = Hierarchy::find($collection->parent);
            $path = $parent_collection->path . "," . $collection->id;
            $collection->path = $path;
            $collection->commercial_mode = $parent_collection->commercial_mode;
            $collection->coin_type = $parent_collection->coin_type;
            $collection->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public  function edit($agent_id)
    {
        # code...
        try {
            $data = Hierarchy::find($agent_id);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $data;
    }

    public  function update($data, $agent_id)
    {

        try {
            $model = Hierarchy::find($agent_id);
            $model->name = $data['name'];
            $model->desc = $data['desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function occupyUpdate($data, $agent_id)
    {

        try {
            $model = Hierarchy::find($agent_id);
            $model->occupy_percent = $data['occupy_percent'];
            $model->occupy_desc = $data['occupy_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function waterUpdate($data, $agent_id)
    {

        try {
            $model = Hierarchy::find($agent_id);
            $model->water_occupy = $data['water_occupy'];
            $model->water_percent = $data['water_percent'];
            $model->water_desc = $data['water_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function balanceUpdate($data, $agent_id)
    {
        try {
            $model = Hierarchy::find($agent_id);
            $model->balance = $model->balance + $data['balance'];
            $model->balance_desc = $data['balance_desc'];
            $model->save();

            $parent = Hierarchy::find($model->parent);
            $parent->balance = $parent->balance - $data['balance'];
            $parent->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
