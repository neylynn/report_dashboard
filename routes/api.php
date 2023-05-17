<?php

use App\Http\Controllers\Api\BotTemplateController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\TelegramApi\GetUpdatesController;
use App\Http\Controllers\Api\GraphChartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post("/{telegramBotId}", [GetUpdatesController::class, 'getUpdates']);
Route::post("/get/botflow", [BotTemplateController::class, 'getBotFlowData']);
Route::post("/save/botflow", [BotTemplateController::class, 'saveBotFlowData']);
Route::post("/{message}", function($message) {
    Log::info($message);
});

Route::get("/get_users", [GraphChartController::class, 'index']);
