<?php

namespace App\Http\Controllers;

use App\Http\Constant;
use App\Http\Response;
use App\Models\Hierarchy;
use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    //

    /** @var \App\Models\User $user **/

    public function login(Request $request)
    {

        if (Auth::attempt(["account" => $request->account, "password" => $request->password])) {

            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $diginity = "Admin";
            $uplevel_id = 0;
            if (!$user->supervisor) {
                $hierarchy = Hierarchy::where("account", $user->account)->first();
                if (is_null($hierarchy)) {
                    return Response::format(310, [], "未有權限登入");
                }
                $hierarchy->last_login = date("Y-m-d h:i:s");
                $hierarchy->save();
                switch ($hierarchy->level) {
                    case Constant::COMPANY_LEVEL:
                        $diginity = Constant::COMPANY_DIGNITY;
                        break;
                    case Constant::STATION_LEVEL:
                        $diginity = Constant::STATION_DIGNITY;
                        break;
                    case Constant::AGENT_LEVEL:
                        $diginity = Constant::AGENT_DIGNITY;
                        break;
                }
                $uplevel_id = $hierarchy->parent;
            }


            $token = $user->createToken(Constant::ADMIN)->accessToken;
            $res = [];
            $res["access_token"] = $token;
            $res['dignity'] = $diginity;
            $res['uplevel_id'] = $uplevel_id;

            DB::table('personal_access_tokens')->where("user_id",$user->id)->delete();
            DB::table('personal_access_tokens')->insert(["user_id" => $user->id, "token" => $token]);

            return response()->json($res);
        } else {
            return Response::format(300, [], "帳號密碼錯誤");
        }
    }
}
