<?php

namespace App\Traits\TelegramService;

use App\Models\Bot;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait GetBotInfo
{

    function getAllBots()
    {
        try {
            return Bot::where('status', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_all_bot_error';
        }
    }

    function getBotDatasByTelegramBotId($telegramBotId)
    {
        try {
            return Bot::where('telegram_bot_id', $telegramBotId)
                    ->where('status', 0)
                    ->first();

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_bot_datas_error';
        }
    }

    function getBotDatasByBotId($botId)
    {
        try {
            return Bot::where('id', $botId)
                    ->where('status', 0)
                    ->first();

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_bot_datas_error';
        }
    }

    function telegramBotIdToBotId($telegramBotId)
    {
        try {
            return Bot::where('telegram_bot_id', $telegramBotId)
                    ->first(['id']);

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_bot_id_error';
        }
    }

    function botIdToTelegramBotId($botId)
    {
        try {
            return Bot::where('id', $botId)
                    ->first(['telegram_bot_id']);

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_telegram_bot_id_error';
        }
    }
}
