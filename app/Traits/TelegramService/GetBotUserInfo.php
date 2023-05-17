<?php

namespace App\Traits\TelegramService;

use App\Models\BotUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait GetBotUserInfo
{
    function telegramUserIdToBotUserId($telegramUserId)
    {
        try {
            return BotUser::where('telegram_user_id', $telegramUserId)
                    ->first(['id']);

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_bot_user_id_error';
        }
    }

    function botUserIdToTelegramUserId($botUserId)
    {
        try {
            return BotUser::where('id', $botUserId)
                    ->first(['telegram_user_id']);

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'get_telegram_user_id_error';
        }
    }

}
