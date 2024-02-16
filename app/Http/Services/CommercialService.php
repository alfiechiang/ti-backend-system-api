<?php

namespace App\Http\Services;

use App\Http\Repositorys\CommercialRepository;

class CommercialService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new CommercialRepository();
    }


    public function list()
    {
        $data = $this->repository->list();
        return $data;
    }
}
