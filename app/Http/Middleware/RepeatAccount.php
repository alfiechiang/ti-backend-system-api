<?php

namespace App\Http\Middleware;

use App\Http\Response;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RepeatAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = User::where("account", $request['account'])->get();
        if ($user->isNotEmpty()) {
            return Response::format(40001, [], "此帳號已重複創建");
        }

        $hierchy = Hierarchy::where("account", $request['account'])->get();
        if ($hierchy->isNotEmpty()) {
            return Response::format(40001, [], "此帳號已重複創建");
        }


        $member = Member::where("account", $request['account'])->get();
        if ($member->isNotEmpty()) {
            return Response::format(40001, [], "此帳號已重複創建");
        }


        // if (strcmp($serverIP, $requestIP) != 0) {
        //     return Response::format(40001, [], "未經授權");
        // }
        return $next($request);
    }
}
