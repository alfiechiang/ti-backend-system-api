<?php

namespace App\Http\Services;

use App\Http\Repositorys\BalanceLogRepository;
use App\Http\Repositorys\CoinRepository;
use App\Http\Repositorys\CompanyRepository;
use App\Http\Repositorys\UserRepository;
use Illuminate\Support\Facades\DB;

class CompanyService
{


    protected $repository;
    protected $userRepository;
    protected $balanceLogRepository;

    public function __construct()
    {
        $this->repository = new CompanyRepository();
        $this->userRepository = new UserRepository();
        $this->balanceLogRepository = new BalanceLogRepository();
    }

    public  function list($per_page)
    {
        $data = $this->repository->list($per_page);
        return $data;
    }


    public  function searchList($per_page, $search)
    {
        $data = $this->repository->searchList($per_page, $search);
        return $data;
    }
    public  function create($data)
    {
        DB::transaction(function () use ($data) {

            $data['level'] = 4; //公司等級
            $data['root_parent'] = 0;
            $data['parent'] = 0;
            $this->repository->create($data);
            unset($data['name']);
            $this->userRepository->create($data);
        });
    }

    public  function edit($company_id)
    {
        $data = $this->repository->edit($company_id);
        $user_data = $this->userRepository->edit($data->account);
        $data['password'] = $user_data->password;
        return $data;
    }

    public function update($data, $company_id)
    {

        $this->repository->update($company_id, $data);
        $this->userRepository->update($company_id, $data);
    }


    public function occupyUpdate($data, $company_id)
    {

        $this->repository->occupyUpdate($data, $company_id);
    }

    public function waterUpdate($data, $company_id)
    {

        $this->repository->waterUpdate($data, $company_id);
    }

    public function balanceUpdate($data, $company_id)
    {

        DB::transaction(function () use ($data, $company_id) {
            $this->balanceLogRepository->create($company_id, $data['balance']);

            $this->repository->balanceUpdate($data, $company_id);
        });
    }
}
