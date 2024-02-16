<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StationRepository
{

    public  function list($per_page)
    {
        try {
            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::STATION_LEVEL)->paginate($per_page);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function searchList($per_page, $account)
    {

        $operator = Auth::user();

        # code...
        if ($operator->account == Constant::SUPERVISOR) {
            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::STATION_LEVEL)
                ->where("account", $account)->paginate($per_page);
        } else {
            $user = Auth::user();
            $hier = Hierarchy::where("account", $user->account)->first();
            $parent = $hier->id;


            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::STATION_LEVEL)
                ->where("parent", $parent)->where("account", $account)->paginate($per_page);
        }

        return $data;
    }

    public  function companyUnderStationList($per_page)
    {


        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $parent = $hier->id;


        try {

            $data = Hierarchy::with('upLevel')
                ->with("coin")
                ->with("commercial")
                ->where("level", Constant::STATION_LEVEL)
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
            $collection->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public  function edit($station_id)
    {
        # code...

        try {
            $data = Hierarchy::find($station_id);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $data;
    }

    public  function update($data, $station_id)
    {

        try {
            $model = Hierarchy::find($station_id);
            $model->name = $data['name'];
            $model->desc = $data['desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function occupyUpdate($data, $station_id)
    {

        try {
            $model = Hierarchy::find($station_id);
            $model->occupy_percent = $data['occupy_percent'];
            $model->occupy_desc = $data['occupy_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function waterUpdate($data, $station_id)
    {

        try {
            $model = Hierarchy::find($station_id);
            $model->water_occupy = $data['water_occupy'];
            $model->water_percent = $data['water_percent'];
            $model->water_desc = $data['water_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);

        }
    }

    public function balanceUpdate($data, $station_id)
    {
        try {
            $model = Hierarchy::find($station_id);
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
