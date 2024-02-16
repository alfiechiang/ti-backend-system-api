<?php

namespace App\Http\Repositorys;

use App\Models\Hierarchy;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class HierarchyRepository
{



    public  function getStationParentId($stationId)
    {
        # code...
        try {
            $data = Hierarchy::find($stationId)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data->parent;
    }

    public  function getMemberParent($agent_id)
    {
        # code...
        try {
            $data = Hierarchy::find($agent_id)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }
}
