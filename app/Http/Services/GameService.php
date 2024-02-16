<?php

namespace App\Http\Services;

use App\Http\Repositorys\GameRepository;

class GameService
{

    protected $repository;


    public function __construct()
    {
        $this->repository = new GameRepository();
    }

    public function list()
    {

        $data = $this->repository->list();
        return $data;
    }
}
