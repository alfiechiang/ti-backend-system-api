<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Http\Paginator;
use App\Models\Hierarchy;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class MachineRepository
{


    public  function create($data)
    {
        # code...
        try {
            Machine::create($data);
        } catch (Exception $e) {
            Log::error($e);

            dd($e);
        }
    }

    public  function destroy($machine_id)
    {
        # code...
        try {
            $machine = Machine::find($machine_id);
            $machine->delete();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public  function edit($machine_id)
    {
        $machine = Machine::find($machine_id);
        # code...
        try {
            $machine = Machine::find($machine_id);
            return $machine;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public  function update($machine_id, $data)
    {
        $machine = Machine::find($machine_id);
        # code...
        try {
            $machine = Machine::find($machine_id);
            $machine->video_address = $data['video_address'];
            $machine->link_address = $data['link_address'];
            $machine->open = $data['open'];
            $machine->image = $data['image'];
            $machine->machine_name = $data['machine_name'];
            $machine->machine_model = $data['machine_model'];
            if (isset($data['coin_name'])) {
                $machine->coin_name = $data['coin_name'];
            } else {
                $machine->coin_name = "";
            }
            $machine->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public function list()
    {
        try {

            $data = Machine::get();
        } catch (Exception $e) {
            dd($e);
        }

        $res = $data->map(function ($item, $key) {
            $item['work'] = $item->work;
            return $item;
        });

        return $res->all();
    }


    public function pageList($data)
    {
        try {
            $res = Machine::paginate($data['per_page']);
        } catch (Exception $e) {
            dd($e);
        }

        $items = collect($res->items());
        $map = $items->map(function ($item, $key) {
            $item['work'] = $item->work;
            return $item;
        });
        $items = $map->all();

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $res->currentPage(),
            $res->lastPage(),
            $res->total()
        );
    }
}
