<?php

namespace App\Http\Controllers\BotTemplate;

use App\Http\Controllers\Controller;
use App\Traits\TelegramService\GetBotInfo;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FlowEditController extends Controller
{
    use GetBotInfo;

    public function index(Request $request, $botId)
    {
        $currentTime = date('h:i A');
        $getBotDatasByBotIdResult = $this->getBotDatasByBotId($botId);

        if ($getBotDatasByBotIdResult == 'get_bot_datas_error') {
            return response()->json(['status' => $getBotDatasByBotIdResult], 500);
        } else if ($getBotDatasByBotIdResult == null) {
            return Redirect('bots');
        }

        $datas = [
            'current_time' => $currentTime,
            'bot_id' => $botId,
            'bot_name' => $getBotDatasByBotIdResult->name,
            'bot_image' => $getBotDatasByBotIdResult->image
        ];

        return view('bot-template.bot-template-edit', compact('datas'));
    }
}
