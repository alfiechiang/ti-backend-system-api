<?php

namespace App\Http\Services;

use App\Http\Constant;
use App\Http\Repositorys\BalanceLogRepository;
use App\Http\Repositorys\StationRepository;
use App\Http\Repositorys\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StationService
{

    protected $repository;
    protected $userRepository;
    protected $balanceLogRepository;

    public function __construct()
    {
        $this->repository = new StationRepository();
        $this->userRepository = new  UserRepository();
        $this->balanceLogRepository = new BalanceLogRepository();
    }


    public  function list($per_page)
    {

        $user = Auth::user();

        //supervisor
        if ($user->supervisor) {
            $data = $this->repository->list($per_page);
        } else {
            $data = $this->repository->companyUnderStationList($per_page);
        }

        return $data;
    }

    public function searchList($per_page, $account)
    {
        $data = $this->repository->searchList($per_page, $account);
        return $data;
    }

    public  function create($data)
    {

        $data['root_parent'] = $data['company_id'];
        $data['parent'] = $data['company_id'];
        $data['level'] = 3; //站長等級

        unset($data['company_id']);

        DB::transaction(function () use ($data) {

            $this->repository->create($data);
            $this->userRepository->create($data);
        });
    }

    public  function edit($station_id)
    {
        $data = $this->repository->edit($station_id);
        $user_data = $this->userRepository->edit($data->account);
        $data['password'] = $user_data->password;
        return $data;
    }

    public function update($data, $station_id)
    {


        $this->repository->update($data, $station_id);
        $this->userRepository->update($station_id, $data);
        # code...
    }

    public function occupyUpdate($data, $station_id)
    {

        $this->repository->occupyUpdate($data, $station_id);
    }

    public function waterUpdate($data, $station_id)
    {

        $this->repository->waterUpdate($data, $station_id);
    }

    public function balanceUpdate($data, $station_id)
    {

        DB::transaction(function () use ($data, $station_id) {
            $this->balanceLogRepository->create($station_id, $data['balance']);
            $this->repository->balanceUpdate($data, $station_id);
        });
    }
}
