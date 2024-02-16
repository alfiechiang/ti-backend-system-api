<?php

use App\Http\ThirdParty\BT\Controllers\BetRecordController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:api'])->group(function () {

    Route::get("/bet_records", [BetRecordController::class, "list"]);

});
