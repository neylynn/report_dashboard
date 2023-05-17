<?php

use App\Http\Controllers\BotTemplate\FlowEditController;
use App\Http\Controllers\SuperAdmin\BotsController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserSettingController;
use App\Http\Controllers\Test\SaveBotTemplateController;
use App\Http\Controllers\Test\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('login');
    return redirect('login');
});




// test
Route::get('/setWebhook', [WebhookController::class, 'setWebhook']);
Route::get('/deleteWebhook', [WebhookController::class, 'deleteWebhook']);
Route::get('/createBotTemplate', [SaveBotTemplateController::class, 'createBotTemplate']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
    ])->group(function () {
    Route::get('/bots', [BotsController::class, 'index'])->name('bots');
    Route::get('/bots/edit/flow/{botId}', [FlowEditController::class, 'index'])->name('edit.template');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'superAdminRoleChecker'
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user-setting', [UserSettingController::class, 'index'])->name('userSetting');
    Route::get('/admins-get', [UserSettingController::class, 'getAdminUsers'])->name('getadmins');
});
