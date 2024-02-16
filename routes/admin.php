<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\BetRecordController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\CommercialController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoinEventController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\StakeController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;


Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [UserController::class, "logout"]);

Route::middleware(['auth:api'])->group(function () {

    Route::get("/z", [UserController::class, "getLevelID"]);
    Route::get("/menu", [MenuController::class, "index"]);
    Route::post("/change_password", [UserController::class, "changePassword"]);
    Route::get("/password", [UserController::class, "passwordIndex"]);
    Route::post("/coin", [CoinController::class, "create"]);
    Route::get("/coin", [CoinController::class, "index"]);
    Route::delete("coin/{coin_id}", [CoinController::class, "destroy"]);
    Route::get("/commercial", [CommercialController::class, "index"]);

    Route::get("/company", [CompanyController::class, "index"]);
    Route::post("/company", [CompanyController::class, "create"])->middleware("repeat.account");
    Route::get("/company/{company_id}/edit", [CompanyController::class, "edit"]);
    Route::put("/company/{company_id}", [CompanyController::class, "update"]);
    Route::put("/company/{company_id}/occupy", [CompanyController::class, "occupyUpdate"]);
    Route::put("/company/{company_id}/water", [CompanyController::class, "waterUpdate"]);
    Route::put("/company/{company_id}/balance", [CompanyController::class, "balanceUpdate"]);
    Route::get("/company_balancelog", [CompanyController::class, "balanceLog"]);

    Route::get("/station/up_level_account", [StationController::class, "upLevelAccount"]);
    Route::get("/station", [StationController::class, "index"]);
    Route::post("/station", [StationController::class, "create"])->middleware("repeat.account");
    Route::get("/station/{station_id}/edit", [StationController::class, "edit"]);
    Route::put("/station/{station_id}", [StationController::class, "update"]);
    Route::put("/station/{station_id}/occupy", [StationController::class, "occupyUpdate"]);
    Route::put("/station/{station_id}/water", [StationController::class, "waterUpdate"]);
    Route::put("/station/{station_id}/balance", [StationController::class, "balanceUpdate"]);
    Route::get("/station_balancelog", [StationController::class, "balanceLog"]);

    Route::get("/agent/up_level_account", [AgentController::class, "upLevelAccount"]);
    Route::get("/agent", [AgentController::class, "index"]);
    Route::post("/agent", [AgentController::class, "create"])->middleware("repeat.account");
    Route::get("/agent/{agent_id}/edit", [AgentController::class, "edit"]);
    Route::put("/agent/{agent_id}", [AgentController::class, "update"]);
    Route::put("/agent/{agent_id}/occupy", [AgentController::class, "occupyUpdate"]);
    Route::put("/agent/{agent_id}/water", [AgentController::class, "waterUpdate"]);
    Route::put("/agent/{agent_id}/balance", [AgentController::class, "balanceUpdate"]);
    Route::get("/agent_balancelog", [AgentController::class, "balanceLog"]);

    Route::get("/member/up_level_account", [MemberController::class, "upLevelAccount"]);
    Route::get("/member", [MemberController::class, "index"]);
    Route::post("/member", [MemberController::class, "create"])->middleware("repeat.account");
    Route::put("/member/{member_id}", [MemberController::class, "update"]);
    Route::get("/member/{member_id}/edit", [MemberController::class, "edit"]);
    Route::get("/member/{member_id}/balance_edit", [MemberController::class, "balanceEdit"]);
    Route::put("/member/{member_id}/balance_update", [MemberController::class, "balanceUpdate"]);
    Route::get("/member_balancelog", [MemberController::class, "balanceLog"]);
    Route::get("/member/coinevent/{member_id}/edit", [CoinEventController::class, "member_edit"]);
    Route::put("/member/coinevent/{member_id}", [CoinEventController::class, "member_update"]);
    Route::get("/member_playerlog", [UserController::class, "palyerBalanceLog"]);

    Route::get("/settle/company", [SettlementController::class, "companyList"]);
    Route::get("/settle/station", [SettlementController::class, "stationList"]);
    Route::get("/settle/agent", [SettlementController::class, "agentList"]);
    Route::get("/settle/member", [SettlementController::class, "memberList"]);

    Route::get("/bet_records/slot", [BetRecordController::class, "slotList"]);
    Route::get("/bet_records/lottery", [BetRecordController::class, "lotteryList"]);
    Route::post("/betrecord/lottery/cancel", [BetRecordController::class, "lotteryBetRecordCancel"]);
    Route::post("/betrecord/slot/cancel", [BetRecordController::class, "slotBetRecordCancel"]);



    Route::get("/machine", [MachineController::class, "index"]);
    Route::post("/machine", [MachineController::class, "create"]);
    Route::delete("/machine/{machine_id}", [MachineController::class, "destroy"]);
    Route::get("/machine/{machine_id}/edit", [MachineController::class, "edit"]);
    Route::put("/machine/{machine_id}", [MachineController::class, "update"]);
    Route::get("/notify", [NotifyController::class, "index"]);
    Route::put("/notify", [NotifyController::class, "update"]);
    Route::post("/uploadImage", [UploadController::class, "uplaod"]);
    Route::get("/game", [GameController::class, "index"]);

    Route::get("/lottery", [LotteryController::class, "index"]);
    Route::get("/lottery/{type}/edit", [LotteryController::class, "edit"]);
    Route::put("/lottery/{type}", [LotteryController::class, "update"]);
    Route::get("/lottery/result", [LotteryController::class, "periodList"]);
    Route::put("/lottery/result/batch", [LotteryController::class, "periodBatchUpdate"]);
    Route::post("/lottery/caculate", [LotteryController::class, "caculate"]);


    Route::get("/stake", [StakeController::class, "index"]);
    Route::put("/stake", [StakeController::class, "batchUpdate"]);

});
Route::post("/kickMember", [UserController::class, "kickMember"]);
