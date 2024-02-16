<?php

namespace App\Http\Repositorys;

use App\Models\Game;

class GameRepository
{

    public function list()
    {

        $data = Game::all();
        return $data;
    }
}
