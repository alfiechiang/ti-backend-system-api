<?php

namespace App\Http\ThirdParty\BT\Controllers;

use App\Http\Constant;
use App\Http\Controllers\Controller;
use App\Http\Response;
use App\Http\Curl;
use App\Http\Services\UserService;
use App\Http\Services\MemberService;
use App\Http\Services\BetRecordService;
use App\Models\CompanyFs;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class BtGameController extends Controller
{
    //測試版
    public $account_id_test    = "466616851145708";  
    public $security_code_test = "c7090b8fa5042db47c732b1bc02fcfcf";  
    public $api_url_test       = "https://game.stgkg.btgame777.com/v2_2"; 

    //正式版
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
        $this->redis =  Redis::connection('gameapi');
        $this->user_service = new UserService();
        $this->member_service = new MemberService();
        $this->bet_record_service = new BetRecordService();
        
    }

    public function encrypt($data){
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

    public function encrypt_test($data){//測試版
        $security_code = $this->security_code_test;
        $str = "security_code={$security_code}&";

        ksort($data);
        foreach ($data as $k => $v) {
            $str .= "{$k}={$v}&";
        }
        $md5str = substr($str,0,-1);
        $encrypt_code = md5($md5str);

        return $encrypt_code;
    }

    public function getBalance()
    {
        $balance = 0;
        $user = Auth::user();
        $member = Member::where("account", $user->account)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/user/get_balance";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $user->account;
            $param['check_code'] = $this->encrypt_test($param);            
        }else{
            $url = $this->api_url."/user/get_balance";
            $param['account_id'] = $this->account_id;
            $param['username'] = $user->account;
            $param['check_code'] = $this->encrypt($param);
        }
        $this->curl->post($url,$param);
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $balance = $result['data']['balance'];
                # code...
            }
        }
        return $balance;
    }

    public function getBalanceUser($account)//帶參數查詢餘額
    {
        $balance = 0;

        $member = Member::where("account", $account)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/user/get_balance";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $account;
            $param['check_code'] = $this->encrypt_test($param);  
        }else{
            $url = $this->api_url."/user/get_balance";
            $param['account_id'] = $this->account_id;
            $param['username'] = $account;
            $param['check_code'] = $this->encrypt($param);
        }
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

    public function lobbyLogin(Request $request)
    {

        $user = Auth::user();

        $member = Member::where("account", $user->account)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/agent/lobby_login";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $user->account;
            $param['lang'] = "zh-tw";
            $param['device'] = "mobile";
            $param['home_url'] = "https://slot.elecslot.club/lobby";
            $param['check_code'] = $this->encrypt_test($param); 
        }else{
            $url = $this->api_url."/agent/lobby_login";
            $param['account_id'] = $this->account_id;
            $param['username'] = $user->account;
            $param['lang'] = "zh-tw";
            $param['device'] = "mobile";
            $param['home_url'] = "https://slot.elecslot.club/lobby";
            $param['check_code'] = $this->encrypt($param);
        }       
        $this->curl->post($url,$param);
        $game_url = "";
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $data = $this->user_service->playerInfo();
                $platform_balance = $data['balance'];

                $game_url = $result['data']['lobby_url'];
                $user_array["account"] = $user->account;
                $info = $this->member_service->getMemberInfo($user_array);
                if (!empty($info)){
                    if ($info['balance_limit'] != 0){
                        if ($info['balance_limit'] < $platform_balance) {
                            return Response::format(5200, [], "BT電子遊戲轉入失敗-餘額大於限額");
                        }
                    }
                }
                if ($platform_balance < 100000) {
                    $amount = $platform_balance;
                }else{
                    $amount = 100000;
                }
                $this->deposit_ingame($amount);
                # code...
            }
        }
        $res[] = $game_url;
        return Response::format(200, $res, "BT電子遊戲大廳成功");
    }

    public function deposit(Request $request)
    {
        $amount = $request->amount;
        if ($amount > 0) {
            $this->deposit_ingame($amount);
            return Response::format(200, [], "BT電子遊戲轉入成功-轉入:".$amount);
        }else {
            return Response::format(4200, [], "BT電子遊戲轉入失敗-轉入額度小於0");
        }

    }

    public function deposit_ingame($amount)
    {
        $user = Auth::user();

        $member = Member::where("account", $user->account)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/user/deposit_amount";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $user->account;
            $param['deposit_amount'] = floatval($amount);
            $param['external_order_id'] = "BT".$this->getOrderId();
            $param['check_code'] = $this->encrypt_test($param); 
        }else{
            $url = $this->api_url."/user/deposit_amount";
            $param['account_id'] = $this->account_id;
            $param['username'] = $user->account;
            $param['deposit_amount'] = floatval($amount);
            $param['external_order_id'] = "BT".$this->getOrderId();
            $param['check_code'] = $this->encrypt($param);
        }
        $this->curl->post($url,$param);
        $response = $this->curl->response;
        $result = json_decode($response, true);
        if (!empty($result)) {
            if ($result['status']['code'] == 1000) {
                $redis_data = json_encode(["amount" => $amount, "datetime" => date("Y-m-d H:i:s")]);
                $this->redis->hset("btGameDeposit" , $user->account, $redis_data);
                $res = $this->user_service->memberBalanceChange($user->account, $amount*-1, "slot_bt");
            }
        }
    }

    public function logout_ingame_user(Request $request)//後台呼叫登出全部在遊戲內的玩家
    {
        $user = $this->redis->hgetall("btGameDeposit");
        $ingame_list =  array_keys($user);
        foreach ($ingame_list as $key => $value) {
            $this->logout($value);
        }
        return Response::format(200, [], "BT電子遊戲登出遊戲內玩家成功");
    }

    public function logout_user(Request $request)
    {
        $user_name = $request->account;
        $result = $this->logout($user_name);
        if ($result) {
            return Response::format(200, [], "BT電子遊戲登出成功");
        }else{
            return Response::format(4200, [], "BT電子遊戲登出失敗");
        }
    }

    public function logout($user_name)
    {

        $member = Member::where("account", $user_name)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/agent/user_logout";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $user_name;
            $param['check_code'] = $this->encrypt_test($param);
        }else{
            $url = $this->api_url."/agent/user_logout";
            $param['account_id'] = $this->account_id;
            $param['username'] = $user_name;
            $param['check_code'] = $this->encrypt($param);
        }
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

    public function withdraw()
    {
        $user = Auth::user();
        $this->withdrawAction($user->account);
        return Response::format(200, [], "BT電子洗分成功");
    }


    public function withdrawAction($account)
    {
        $open_score = "";
        $balance = $this->getBalanceUser($account);

        $member = Member::where("account", $account)->first();
        if ($member->has_test_account) {
            $url = $this->api_url_test."/user/withdraw_amount";
            $param['account_id'] = $this->account_id_test;
            $param['username'] = $account;
            $param['take_all'] = "true";
            $param['external_order_id'] = "BT".$this->getOrderId();
            $param['check_code'] = $this->encrypt_test($param);
        }else{
            $url = $this->api_url."/user/withdraw_amount";
            $param['account_id'] = $this->account_id;
            $param['username'] = $account;
            $param['take_all'] = "true";
            $param['external_order_id'] = "BT".$this->getOrderId();
            $param['check_code'] = $this->encrypt($param);
        }
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

    public function getOrderId()
    {
        $order_id = number_format(microtime(true),0,'','').rand(100,999);
        return $order_id;
    }


}

?>