<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuRepository
{


    public  function list()
    {
        # code...
        try {

            $user = Auth::user();
            if ($user->account == Constant::SUPERVISOR) {
                return  Menu::all();
            }

            $role = Role::find($user->role_id);
            $menus = $role->menus()->get();
            return $menus;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
