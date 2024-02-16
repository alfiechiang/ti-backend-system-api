<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\BalanceLog;
use App\Models\Hierarchy;
use App\Models\Member;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BalanceLogRepository
{

    protected $in_account;
    protected $in_name;
    protected  $out_account;
    protected  $out_name;
    protected  $operator;
    protected  $operator_time;
    protected  $balance_log;
    protected $balance_before;
    protected $balance_after;


    public  function ownList($own_account)
    {
        try {
            $data = BalanceLog::where('in_account', $own_account)->orWhere('out_account', $own_account)->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $data;
    }

  

    public  function searchList($data)
    {

        $account =$data['account'];
        $parent_account =$data['parent_account'];

        try {

            $Builder =BalanceLog::where("in_account", $account)->where("out_account",$parent_account)
            ->orWhere("in_account", $parent_account)->where("out_account",$account);

            if(!empty($data['starttime'])&&!empty($data['endtime'])){
                $Builder=$Builder->whereBetween("operator_time",[$data['starttime'],$data['endtime']]);
            }

            $Builder = $Builder->orderBy('created_at', 'desc');

            if(isset($data['per_page'])){
                $Builder = $Builder->paginate($data['per_page']);
            }

            if(!isset($data['per_page'])){
                $Builder = $Builder->get();
            }
            
           $data= $Builder;
          
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function memberCreateLog($hierId, $balance)
    {

        if ($balance > 0) {
            $this->memberTranferIn($hierId, $balance);
        } else {
            $this->memberTranferOut($hierId, $balance);
        }

        BalanceLog::create([
            "out_account" => $this->out_account,
            "out_name" => $this->out_name,
            "in_account" => $this->in_account,
            "in_name" => $this->in_name,
            "balance" => $balance,
            "balance_log" => $this->balance_log,
            "operator" => $this->operator,
            "operator_time" => $this->operator_time
        ]);
    }

    public function memberTranferIn($memberId, $balance)
    {

        $member = Member::Find($memberId);

        $hierarchy_parent = Hierarchy::Find($member->agent_id);
        $this->out_account = $hierarchy_parent->account;
        $this->out_name = $hierarchy_parent->name;
        $this->in_account = $member->account;
        $this->in_name = $member->name;
        $this->operator = (Auth::user())->account;
        $this->operator_time = (new DateTime())->format('Y-m-d H:i:s');
        $this->balance_before = $member->balance;
        $this->balance_after = $member->balance + $balance;
        $this->balance_log = "[異動前:$this->balance_before ] [異動後:$this->balance_after]";
    }

    public function memberTranferOut($memberId, $balance)
    {

        $member = Member::Find($memberId);
        $hierarchy_parent = Hierarchy::Find($member->agent_id);
        $this->in_account = $hierarchy_parent->account;
        $this->in_name = $hierarchy_parent->name;
        $this->out_account = $member->account;
        $this->out_name = $member->name;
        $this->operator = (Auth::user())->account;
        $this->operator_time = (new DateTime())->format('Y-m-d H:i:s');
        $this->balance_before = $member->balance;
        $this->balance_after = $member->balance + $balance;
        $this->balance_log = "[異動前:$this->balance_before ] [異動後:$this->balance_after]";
    }



    public function create($hierId, $balance)
    {

        if ($balance > 0) {
            $this->transferIn($hierId, $balance);
        } else {
            $this->transferout($hierId, $balance);
        }

        BalanceLog::create([
            "out_account" => $this->out_account,
            "out_name" => $this->out_name,
            "in_account" => $this->in_account,
            "in_name" => $this->in_name,
            "balance" => $balance,
            "balance_log" => $this->balance_log,
            "operator" => $this->operator,
            "operator_time" => $this->operator_time
        ]);
    }



    public function transferIn($hierId, $balance)
    {


        $hierarchy = Hierarchy::Find($hierId);
        if ($hierarchy->parent == 0) {

            $this->out_account = Constant::SUPERVISOR;
            $this->out_name = '總公司';
        } else {
            $hierarchy_parent = Hierarchy::Find($hierarchy->parent);
            $this->out_account = $hierarchy_parent->account;
            $this->out_name = $hierarchy_parent->name;
        }

        $this->in_account = $hierarchy->account;
        $this->in_name = $hierarchy->name;
        $this->operator = (Auth::user())->account;
        $this->operator_time = (new DateTime())->format('Y-m-d H:i:s');
        $this->balance_before = $hierarchy->balance;
        $this->balance_after = $hierarchy->balance + $balance;
        $this->balance_log = "[異動前:$this->balance_before ] [異動後:$this->balance_after]";
    }

    public function transferout($hierId, $balance)
    {

        $hierarchy = Hierarchy::Find($hierId);
        if ($hierarchy->parent == 0) {
            $this->in_account = Constant::SUPERVISOR;
            $this->in_name = '總公司';
        } else {
            $hierarchy_parent = Hierarchy::Find($hierarchy->parent);
            $this->in_account = $hierarchy_parent->account;
            $this->in_name = $hierarchy_parent->name;
        }

        $this->out_account = $hierarchy->account;
        $this->out_name = $hierarchy->name;
        $this->operator = (Auth::user())->account;
        $this->operator_time = (new DateTime())->format('Y-m-d H:i:s');
        $this->balance_before = $hierarchy->balance;
        $this->balance_after = $hierarchy->balance + $balance;
        $this->balance_log = "[異動前:$this->balance_before ] [異動後:$this->balance_after]";
    }
}
