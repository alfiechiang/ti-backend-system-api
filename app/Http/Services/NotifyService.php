<?php

namespace App\Http\Services;

use App\Http\Repositorys\NotifyRepository;

class NotifyService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new NotifyRepository();
    }

    public function list()
    {
        $data = $this->repository->list();
        return $data;
    }

    public function update($data)
    {
        $this->repository->update($data);
    }
}
