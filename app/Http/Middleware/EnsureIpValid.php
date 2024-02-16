<?php

namespace App\Http\Middleware;

use App\Http\Response;
use Closure;
use Illuminate\Http\Request;

class EnsureIpValid
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


        $serverIP = env("SERVER_IP");

        $requestIP = $request->ip();
        if (strcmp($serverIP, $requestIP) != 0) {
            //sreturn Response::format(40001, [], "未經授權");
        }

        return $next($request);
    }
}
