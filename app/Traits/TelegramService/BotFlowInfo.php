<?php

namespace App\Traits\TelegramService;

use App\Models\BotTemplate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait BotFlowInfo {

    function getBotFlow($botId)
    {
        try {
            $result = BotTemplate::where('bot_id', $botId)
                            ->first(['flow']);

            return $result;

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get-bot-flow-error';
        }
    }

    function saveBotFlow($botId, $botFlow)
    {
        try {

            $result = BotTemplate::where('bot_id', $botId)
                        ->update([
                            'flow' => $botFlow
                        ]);

            return $result;

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'save-bot-flow-error';
        }
    }
}
