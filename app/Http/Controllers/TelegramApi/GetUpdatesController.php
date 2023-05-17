<?php

namespace App\Http\Controllers\TelegramApi;

use App\Http\Controllers\Controller;
use App\TelegramServiceProviders\TelegramServiceUpdate;
use App\Traits\TelegramService\GetBotService;
use Illuminate\Http\Request;

class GetUpdatesController extends Controller
{
    use GetBotService;

    private $telegramServiceUpdate;

    public function __construct(TelegramServiceUpdate $telegramServiceUpdate)
    {
        $this->telegramServiceUpdate = $telegramServiceUpdate;
    }

    public function getUpdates(Request $request, $telegramBotId)
    {

        $update = $request;
        $this->telegramServiceUpdate->set($this->getService($telegramBotId))->getUpdates($update, $telegramBotId);

        return response()->json(['status' => 'success'], 200);

    }
}
