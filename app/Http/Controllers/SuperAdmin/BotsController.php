<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Traits\TelegramService\GetBotInfo;
use Illuminate\Http\Request;

class BotsController extends Controller
{
    use GetBotInfo;

    public function index()
    {
        $getAllBotsResult = $this->getAllBots();
        if ($getAllBotsResult == 'get_all_bot_error') {
            return response()->json(['status' => $getAllBotsResult], 500);
        }

        $data = [
            'botsDatas' => $getAllBotsResult
        ];

        return view('bot-template.bots', compact('data'));
    }
}
