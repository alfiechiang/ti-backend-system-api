<?php

namespace App\Http\Services;

use App\Http\Repositorys\MachineRepository;
use Illuminate\Support\Facades\Auth;

class MachineService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new MachineRepository();
    }

    public  function create($data)
    {

        $this->repository->create($data);
    }


    public  function destroy($machine_id)
    {
        $this->repository->destroy($machine_id);
    }

    public  function edit($machine_id)
    {
        $data = $this->repository->edit($machine_id);
        return $data;
    }

    public  function update($machine_id, $data)
    {
        $this->repository->update($machine_id, $data);
    }

    public  function list($data)
    {


        if (isset($data['page'])) {
            $res = $this->repository->pageList($data);
        } else {
            $res = $this->repository->list();
        }

        return $res;
    }

   
}
