<?php

namespace App\Traits\TelegramService;

use App\Models\BotUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait SaveBotUser
{
    use TagBotUser;
    use GetBotInfo;
    use GetBotUserInfo;
    use BotUserEngagement;

    function saveBotUser($botId, $telegramUserId, $name, $userName, $dateTime)
    {

        // check bot user is already saved
        $checkBotUserSavedResult = $this->checkBotUserSaved($telegramUserId);
        if ($checkBotUserSavedResult == 'check-bot-user-saved-error') {
            return response()->json(['status' => $checkBotUserSavedResult], 500);
        }

        if (! $checkBotUserSavedResult > 0) {
            // trait SaveBotUser [to save new bot user]
            $saveNewBotUserResult = $this->saveNewBotUser($telegramUserId, $name, $userName, 0, $dateTime);
            if ($saveNewBotUserResult == 'save-bot-user-error') {
                return response()->json(['status' => $saveNewBotUserResult], 500);
            }
            // get bot user id create result
            $botUserId = $saveNewBotUserResult->id;
            // trait tagBotUser [to tag bot user]
            $tagBotUserResult = $this->tagBotUser($botId, $botUserId, $dateTime);
            $tagBotUserResult= json_decode(json_encode($tagBotUserResult),true);
            // Log::info($tagBotUserResult['original']['tag-bot-user']);
            $botUserTagId = $tagBotUserResult['original']['tag-bot-user'];
            $this->engageUser($botUserTagId, $dateTime);
            return $botUserId;
        } else {
            // trait GetBotUserInfo [to get bot user id by telegram user id]
            $telegramUserIdToBotUserIdResult = $this->telegramUserIdToBotUserId($telegramUserId);
            if ($telegramUserIdToBotUserIdResult == 'get_bot_user_id_error') {
                return response()->json(['status' => $telegramUserIdToBotUserIdResult], 500);
            }
            $botUserId = $telegramUserIdToBotUserIdResult->id;
            // trait tagBotUser [to tag bot user]
            $tagBotUserResult = $this->tagBotUser($botId, $botUserId, $dateTime);
            $tagBotUserResult= json_decode(json_encode($tagBotUserResult),true);
            // Log::info($tagBotUserResult['original']['tag-bot-user']);
            $botUserTagId = $tagBotUserResult['original']['tag-bot-user'];
            $this->engageUser($botUserTagId, $dateTime);
            return $botUserId;
        }
    }

    private function checkBotUserSaved($telegramUserId)
    {
        try {
            $result = BotUser::where('telegram_user_id', $telegramUserId)
                        ->get()
                        ->count();

            return $result;

        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'check-bot-user-saved-error';
        }
    }

    private function saveNewBotUser($telegramUserId, $name, $userName, $status, $dateTime)
    {
        try {
            $result = BotUser::create([
                'telegram_user_id' => $telegramUserId,
                'name' => $name,
                'user_name' => $userName,
                'status' => $status,
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ]);

            Log::debug('new bot user saved.');
            return $result;

        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'save-bot-user-error';
        }
    }
}
