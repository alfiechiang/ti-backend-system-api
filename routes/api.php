<?php

use App\Http\Controllers\MachineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BetRecordController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\CoinEventController;
use App\Http\Controllers\LotteryNumberController;
use App\Http\Controllers\LotteryOddController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\TestController;
use App\Http\ThirdParty\BT\Controllers\BtGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*˚
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/test", [TestController::class, "test"]);


Route::get('/', [UserController::class, "backToHall"])->name("login");





Route::post("/login", [UserController::class, "login"]);

Route::post("/lottery/result", [SettlementController::class, "calcLotteryResult"]);


Route::middleware(['auth:api'])->group(function () {

    Route::get("/hello", [UserController::class, "hello"]);
    Route::get("/notify", [NotifyController::class, "index"]);
    Route::post("/change_password", [UserController::class, "changePassword"]);
    Route::post("/logout", [UserController::class, "logout"]);
    Route::get("/machine", [MachineController::class, "index"]);

    Route::get("/player/info", [UserController::class, "playerInfo"]);
    Route::post("/player/balance", [UserController::class, "memberBalanceChange"]);
    Route::post("/player/coinevent/balance", [CoinEventController::class, "member_balance"]);
    Route::get("/player/balancelog", [UserController::class, "palyerBalanceLog"]);


    Route::post("/betrecord/slot", [BetRecordController::class, "slotCreate"])->middleware("ip");
    Route::post("/betrecord/lottery", [BetRecordController::class, "lotteryCreate"])->middleware("ip");
    Route::post("/betrecord/lottery_batch", [BetRecordController::class, "batchLotteryCreate"])->middleware("ip");
    Route::get("/betrecord/lottery", [BetRecordController::class, "lotteryBetRecords"]);

    Route::get("/lottery/{type}", [LotteryController::class, "findOne"]);
    Route::get("/lottery", [LotteryController::class, "list"]);

    Route::get("/lotteryodd", [LotteryOddController::class, "typeList"]);
    Route::get("/lotteryodd/option", [LotteryOddController::class, "optionList"]);
    Route::get("/lotterynumber", [LotteryNumberController::class, "typeLotteryNumber"]);

    



   Route::post("/bt_game/loginGame", [BtGameController::class, "lobbyLogin"]);
    Route::post("/bt_game/checkBalance", [BtGameController::class, "getBalance"]);
    Route::post("/bt_game/deposit", [BtGameController::class, "deposit"]);
    Route::post("/bt_game/withdraw", [BtGameController::class, "withdraw"]);
});
    Route::post("/bt_game/logoutIngameUser", [BtGameController::class, "logout_ingame_user"]);
    Route::post("/bt_game/logoutUser", [BtGameController::class, "logout_user"]);
    