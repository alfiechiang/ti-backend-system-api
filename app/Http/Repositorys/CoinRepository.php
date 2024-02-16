<?php

namespace App\Http\Repositorys;

use App\Models\Coin;
use App\Models\Hierarchy;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class CoinRepository
{

    public  function create($data)
    {
        # code...
        try {
            Coin::create($data);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function list()
    {

        try {
            $data = Coin::select("id as coin_id", "name")->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function searchList($coin_name)
    {

        try {
            $data = Coin::select("id as coin_id", "name")->where("name", $coin_name)->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }


    public function accountCoinList($username)
    {

        try {

            $hierarchy = Hierarchy::where("account", $username)->first();
            $data = Coin::select("id as coin_id", "name")->Find($hierarchy->coin_type);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }


        return $data;
    }

    public function destroy($coinId)
    {

        try {
            $coin = Coin::find($coinId);
            $coin->delete();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
