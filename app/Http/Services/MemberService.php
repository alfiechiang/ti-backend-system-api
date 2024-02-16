<?php

namespace App\Http\Services;

use App\Http\Constant;
use App\Http\Repositorys\BalanceLogRepository;
use App\Http\Repositorys\CoinEventRepository;
use App\Http\Repositorys\HierarchyRepository;
use App\Http\Repositorys\MemberGameStatusRepository;
use App\Http\Repositorys\MemberRepository;
use App\Http\Repositorys\UserRepository;
use App\Models\Hierarchy;
use App\Models\MemberGameStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberService
{

    protected $repository;
    protected $hierarchyRepository;
    protected $memberGameStatusRepository;
    protected $balanceLogRepository;
    protected $userRepository;
    protected $coinEventRepository;

    public function __construct()
    {
        $this->repository = new MemberRepository();
        $this->hierarchyRepository = new HierarchyRepository();
        $this->memberGameStatusRepository = new MemberGameStatusRepository();
        $this->balanceLogRepository = new BalanceLogRepository();
        $this->userRepository = new UserRepository();
        $this->coinEventRepository = new CoinEventRepository;
    }

    public function upLevelAccount()
    {
        //判斷身分
        $user = Auth::user();
        $account = $user->account;
        if ($user->supervisor) {
            $data = [];

            $agents = Hierarchy::select('id as agent_id', 'account as agent_account')
                ->where('level', Constant::AGENT_LEVEL)->get();
            $data[] = $agents;
            return  $data;
        }

        $hierarchy = Hierarchy::where("account", $account)->first();
        $level = $hierarchy->level;
        $data = [];

        switch ($level) {
            case 4:
                //公司底下代理
                $agents = Hierarchy::select('id as agent_id', 'account as agent_account')
                    ->where('path', 'like', '%' . $hierarchy->id . '%')->where('level', Constant::AGENT_LEVEL)->get();
                $data[] = $agents;
                break;
                //->where('name', 'like', 'T%')
            case 3:
                $agents = Hierarchy::select('id as agent_id', 'account as agent_account')
                    ->where('path', 'like', '%' . $hierarchy->id . '%')->where('level', Constant::AGENT_LEVEL)->get();
                $data[] = $agents;
                break;
            case 2:
                $agent = [];
                $agent['agent_id'] = $hierarchy->id;
                $agent['agent_account'] = $hierarchy->account;
                $data[] = $agent;
        }

        return $data;
    }


    public  function list($per_page)
    {

        $user = Auth::user();

        if ($user->supervisor) {

            $data = $this->repository->list($per_page);
        } else {

            switch ($user->level) {
                case Constant::COMPANY_LEVEL:
                    $data = $this->repository->companyUnderMemberList($per_page);
                    break;
                case Constant::STATION_LEVEL:
                    $data = $this->repository->stationUnderMemberList($per_page);
                    break;
                case Constant::AGENT_LEVEL:
                    $data = $this->repository->agentUnderMemberList($per_page);
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
            $member_id = $this->repository->create($data);
            $this->userRepository->create($data);
            $data['member_id'] = $member_id;
            $this->memberGameStatusRepository->create($data);
            $this->coinEventRepository->member_default_add($member_id);
        });
    }

    public  function edit($member_id)
    {
        return $this->repository->edit($member_id);
    }


    public function balanceEdit($member_id)
    {
        $data = $this->repository->balanceEdit($member_id);
        $parent_collection = $this->hierarchyRepository->getMemberParent($data->agent_id);
        $data['uplevel_balance'] = $parent_collection->balance;
        unset($data['agent_id']);

        return $data;
    }

    public function update($data, $member_id)
    {
        $games = $data['games'];
        unset($data['games']);
        $this->repository->update($data, $member_id);
        if (!empty($data['password'])) {
            $this->repository->passpordUpdate($member_id, bcrypt($data['password']));
        }
        $this->memberGameStatusRepository->update($games, $member_id);
        # code...
    }

    public function balanceUpdate($data, $member_id)
    {
        DB::transaction(function () use ($data, $member_id) {
            $this->balanceLogRepository->memberCreateLog($member_id, $data['balance']);
            $this->repository->balanceUpdate($data, $member_id);
        });
    }

    public function getMemberInfo($account)
    {
        $data = $this->repository->findOne($account);
        return $data;
    }    
}
