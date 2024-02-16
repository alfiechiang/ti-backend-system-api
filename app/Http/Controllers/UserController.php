<?php

namespace App\Http\Controllers;

use App\Http\Constant;
use App\Http\ThirdParty\BT\Services\BtGameService;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Services\UserService;
use App\Models\Member;
use App\Models\MemberGameStatus;
use App\Models\OauthAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    protected $service;

    protected $btGameService;

    public function __construct()
    {

        $this->service = new UserService();
        $this->btGameService =new BtGameService();

    }


    public function login(Request $request)
    {

        $source = $request->header("source");
        $enter = false;

        if ($source == Constant::ADMIN ||  $source == Constant::SLOT ||  $source == Constant::LOTTERY
        ||$source == Constant::HALL) {
            $enter = true;
        }

        if (!$enter) {
            return Response::format("310", [], "非法登入");
        }

        $repeat_login = Redis::get("member:login:" . $request->account);
        if (!is_null($repeat_login)){ //!=null 代表已登入過
            $user=User::where("account",$request->account)->first();
            OauthAccessToken::where("user_id",$user->id)->delete();
        }

        if (Auth::attempt(["account" => $request->account, "password" => $request->password])) {

            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $member = Member::where("account", $user->account)->first();
            if ($member->freeze_status) {
                return Response::format("310", [], "帳號凍結");
            }

            $token = $user->createToken($source)->accessToken;
            $res = [];
            $res["access_token"] = $token;

            DB::table('personal_access_tokens')->where("user_id",$user->id)->delete();
            DB::table('personal_access_tokens')->insert(["user_id" => $user->id, "token" => $token]);
            Redis::set("member:login:" . $user->account, $token, 'ex', 86400);

            $slot =MemberGameStatus::where("member_id",$member->id)->where("game_name","slot")->first();
            $lottery =MemberGameStatus::where("member_id",$member->id)->where("game_name","lottery")->first();
            $slot_bt =MemberGameStatus::where("member_id",$member->id)->where("game_name","bt")->first();

            $res['slot_open'] = $slot->open;
            $res['lottery_open'] = $lottery->open;
            $res['bt_open']=$slot_bt->open;

            return response()->json($res);
        } else {
            return Response::format("300", [], "帳號密碼錯誤");
        }
    }

    public function logout(Request $request)
    {

        if (Auth::check()) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->token();
            $token->revoke();
            DB::table("oauth_access_tokens")->where("user_id", $user->id)->delete();
            DB::table("personal_access_tokens")->where("user_id", $user->id)->delete();
            Redis::del("member:login:" . $user->account);
        }

        return Response::format(200, [], "登出成功");
    }

    public function kickMember(Request $request)
    {

        $data = $request->all();
        $member_id = $data['member_id'];
        $member = Member::find($member_id);
        $user = User::where("account", $member->account)->first();
        DB::table("oauth_access_tokens")->where("user_id", $user->id)->delete();
        DB::table("personal_access_tokens")->where("user_id", $user->id)->delete();

        Redis::del("member:login:" . $user->account);
        ## 第三方  BT電子登出
        $this->btGameService->logout($member->account);
        return Response::format(200, [], "踢線成功");
    }

    public function hello(Request $request)
    {

        return Response::format(200, [], "hello成功");
    }

    public function backToHall(Request $request)
    {

        $token = $request->bearerToken('Authorization');
        $access = DB::table('personal_access_tokens')->where("token", $token)->first();
        if (is_null($access)) {
            return Response::format(401, [], "Unauthenticated");
        }
        $user_id = $access->user_id;
        DB::table('personal_access_tokens')->where("user_id", $user_id)->delete();
        DB::table('oauth_access_tokens')->where("user_id", $user_id)->delete();
        $user = User::find($user_id);
        Redis::del("member:login:" . $user->account);

        return Response::format(401, [], "Unauthenticated");
    }


    public function passwordIndex(Request $request)
    {

        $user_info = $this->service->passwordIndex();
        return Response::format(200, $user_info, "修改密碼成功");
    }

    public function changePassword(Request $request)
    {

        $user = Auth::user();
        $user_id = $user->id;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if (strcmp($new_password, $confirm_password) != 0) {
            return Response::format(310, [], "新密碼與確認密碼不相等");
        }
        $this->service->changePassword($user_id, $new_password);

        return Response::format(200, [], "修改密碼成功");
    }


    public function getLevelID(Request $request)
    {


        $data = $request->all();
        $uplevel_id = $this->service->getLevelID($data['account']);

        return Response::format(200, ["uplevel_id" => $uplevel_id], "取得上級ID成功");
    }

    public function playerInfo(Request $request)
    {

        $data = $this->service->playerInfo();

        return Response::format(200, $data, "玩家個人資訊成功");
    }


    public function memberBalanceChange(Request $request)
    {

        $data = $request->all();
        $res = $this->service->memberBalanceChange($data['account'], $data['balance'], $data['category']);

        return Response::format(200, $res, "玩家餘額成功");
    }

    public function palyerBalanceLog(Request $request)
    {

        $data = $request->all();
        $res = $this->service->palyerBalanceLog($data['account'], $data);
        return Response::format(200, $res, "玩家餘額日誌成功");
    }
}
