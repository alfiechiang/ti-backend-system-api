<?php

namespace App\Http\Services;

use App\Http\Constant;
use App\Http\Repositorys\AgentRepository;
use App\Http\Repositorys\BalanceLogRepository;
use App\Http\Repositorys\HierarchyRepository;
use App\Http\Repositorys\StationRepository;
use App\Http\Repositorys\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentService
{

    protected $repository;
    protected $hierarchyRepository;
    protected $userRepository;
    protected $balanceLogRepository;


    public function __construct()
    {
        $this->repository = new AgentRepository();
        $this->hierarchyRepository = new HierarchyRepository();
        $this->userRepository = new UserRepository();
        $this->balanceLogRepository = new BalanceLogRepository();
    }


    public  function list($per_page)
    {

        $user = Auth::user();

        //supervisor
        if ($user->supervisor) {

            $data = $this->repository->list($per_page);
        } else {


            switch ($user->level) {
                case Constant::COMPANY_LEVEL:
                    $data = $this->repository->companyUnderAgentList($per_page);
                    break;
                case Constant::STATION_LEVEL:
                    $data = $this->repository->stationUnderAgentList($per_page);
                    break;
            }
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


        DB::transaction(function () use ($data) {
            $data['root_parent'] = $this->hierarchyRepository->getStationParentId($data['station_id']);
            $data['parent'] = $data['station_id'];
            $data['level'] = Constant::AGENT_LEVEL; //代理等級
            unset($data['company_id']);
    
            $this->repository->create($data);
            $this->userRepository->create($data);
        });
    }

    public  function edit($agent_id)
    {
        $data = $this->repository->edit($agent_id);
        $user_data = $this->userRepository->edit($data->account);
        $data['password'] = $user_data->password;
        return $data;
    }


    public function update($data, $agent_id)
    {

        $this->repository->update($data, $agent_id);
        $this->userRepository->update($agent_id, $data);

        # code...

    }

    public function occupyUpdate($data, $agent_id)
    {

        $this->repository->occupyUpdate($data, $agent_id);
    }

    public function waterUpdate($data, $agent_id)
    {

        $this->repository->waterUpdate($data, $agent_id);
    }

    public function balanceUpdate($data, $agent_id)
    {

        DB::transaction(function () use ($data, $agent_id) {
            $this->balanceLogRepository->create($agent_id, $data['balance']);
            $this->repository->balanceUpdate($data, $agent_id);
        });
    }
}
