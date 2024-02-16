<?php

namespace App\Http\ThirdParty\BT\Services;

use App\Http\Services\UserService;
use App\Http\Services\BetRecordService;
use App\Http\Curl;


class BtGameService
{

    public $account_id    = "466616851145708";
    public $security_code = "c7090b8fa5042db47c732b1bc02fcfcf";
    public $api_url       = "https://game.stgkg.btgame777.com/v2_2";

    public $curl;
    public $redis;


    protected $user_service;
    protected $bet_record_service;



    public function __construct()
    {
        $this->curl = new Curl();
        $this->user_service = new UserService();
        $this->bet_record_service = new BetRecordService();
    }
    
    public function logout($user_name)
    {
        $url = $this->api_url."/agent/user_logout";
        $param['account_id'] = $this->account_id;
        $param['username'] = $user_name;
        $param['check_code'] = $this->encrypt($param);
        $this->curl->post($url,$param);
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $this->withdrawAction($user_name);
            }
            return true;
        }else{
            return false;
        }
        
    }

    private function encrypt($data){
        $security_code = $this->security_code;
        $str = "security_code={$security_code}&";

        ksort($data);
        foreach ($data as $k => $v) {
            $str .= "{$k}={$v}&";
        }
        $md5str = substr($str,0,-1);
        $encrypt_code = md5($md5str);

        return $encrypt_code;
    }

    private function withdrawAction($account)
    {
        $open_score =
        $balance = $this->getBalanceUser($account);

        $url = $this->api_url."/user/withdraw_amount";
        $param['account_id'] = $this->account_id;
        $param['username'] = $account;
        $param['take_all'] = "true";
        $param['external_order_id'] = "BT".$this->getOrderId();
        $param['check_code'] = $this->encrypt($param);
        $this->curl->post($url,$param);
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $res = $this->user_service->memberBalanceChange($account, $balance, "slot_bt");
                $last_deposit = $this->redis->hget("btGameDeposit" , $account);
                if (!empty($last_deposit)) {
                    $open_score = json_decode($last_deposit, true);
                    $this->betRecord($open_score, $balance, $account);
                }
                $this->redis->hdel("btGameDeposit" , $account);
            }
        }
    }

    private function getBalanceUser($account)//帶參數查詢餘額
    {
        $balance = 0;
        $url = $this->api_url."/user/get_balance";
        $param['account_id'] = $this->account_id;
        $param['username'] = $account;
        $param['check_code'] = $this->encrypt($param);
        $this->curl->post($url,$param);
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $balance = $result['data']['balance'];
            }
        }
        return $balance;
    }


    private function getOrderId()
    {
        $order_id = number_format(microtime(true),0,'','').rand(100,999);
        return $order_id;
    }


    public function betRecord($open_score, $wash_score, $account)
    {
        $data['open_score'] = floatval($open_score['amount']);
        $data['open_score_time'] = $open_score['datetime'];
        $data['wash_score'] = floatval($wash_score);
        $data['result'] = $data['wash_score'] - $data['open_score'];
        $data['account'] = $account;
        $data['wash_score_time'] = date("Y-m-d H:i:s");
        $data['type'] = "BT";
        $result = $this->bet_record_service->slotCreate($data);

    }







}
