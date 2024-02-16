<?php


use App\Http\ThirdParty\BT\Controllers\BtGameController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::post("/loginGame", [BtGameController::class, "lobbyLogin"]);
    Route::post("/checkBalance", [BtGameController::class, "getBalance"]);
    Route::post("/deposit", [BtGameController::class, "deposit"]);
    Route::post("/withdraw", [BtGameController::class, "withdraw"]);
});
Route::post("/logoutIngameUser", [BtGameController::class, "logout_ingame_user"]);
Route::post("/logoutUser", [BtGameController::class, "logout_user"]);
