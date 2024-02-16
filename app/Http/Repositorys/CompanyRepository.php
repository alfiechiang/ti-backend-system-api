<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class CompanyRepository
{

    public  function list($per_page)
    {
        # code...
        try {
            $data = Hierarchy::where('level', Constant::COMPANY_LEVEL)
                ->with("coin")
                ->with("commercial")
                ->paginate($per_page);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function searchList($per_page, $data)
    {
        $Builder = Hierarchy::where('level', Constant::COMPANY_LEVEL)
            ->with("coin")
            ->with("commercial");
        if (!empty($data['account'])) {
            $Builder = $Builder->where('account', $data['account']);
        }
        $data = $Builder->paginate($per_page);
        return $data;
    }


    public  function create($data)
    {
        # code...
        try {
            $collection = Hierarchy::create($data);
            $collection->path = $collection->id;
            $collection->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public  function edit($company_id)
    {

        try {
            $data = Hierarchy::find($company_id);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public  function update($company_id, $data)
    {

        try {
            $model = Hierarchy::find($company_id);
            $model->name = $data['name'];
            $model->desc = $data['desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public function occupyUpdate($data, $company_id)
    {

        try {
            $model = Hierarchy::find($company_id);
            $model->occupy_percent = $data['occupy_percent'];
            $model->occupy_desc = $data['occupy_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function waterUpdate($data, $company_id)
    {

        try {
            $model = Hierarchy::find($company_id);
            $model->water_occupy = $data['water_occupy'];
            $model->water_percent = $data['water_percent'];
            $model->water_desc = $data['water_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function balanceUpdate($data, $company_id)
    {
        try {
            $model = Hierarchy::find($company_id);
            $model->balance = $model->balance + $data['balance'];
            $model->balance_desc = $data['balance_desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
