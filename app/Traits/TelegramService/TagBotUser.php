<?php

namespace App\Traits\TelegramService;

use App\Models\BotUserTag;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait TagBotUser
{

    function checkBotUserTagged($botId, $botUserId)
    {
        try {
            $result = BotUserTag::where('bot_id', $botId)
                        ->where('bot_user_id', $botUserId)
                        ->get()
                        ->count();

            return $result;

        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'check-bot-user-tagged-error';
        }
    }

    function tagBotUser($botId, $botUserId, $dateTime)
    {
        // check bot user is already tag with that bot
        $checkBotUserTaggedResult = $this->checkBotUserTagged($botId, $botUserId);
        if ($checkBotUserTaggedResult == 'check-bot-user-tagged-error') {
            return response()->json(['status' => $checkBotUserTaggedResult], 500);
        }

        if (! $checkBotUserTaggedResult > 0) {
            // trait TagBotUser [to tag new bot user]
            $tagNewBotUserResult = $this->tagNewBotUser($botId, $botUserId, $dateTime);
            if ($tagNewBotUserResult == 'tag-new-bot-user-error') {
                return response()->json(['status' => $tagNewBotUserResult], 500);
            }

            return response()->json(['tag-bot-user' => $tagNewBotUserResult], 200);
        } else {
            // trait TagBotUser [to update tag bot user]
            $updateTagBotUserResult = $this->updateTagBotUser($botId, $botUserId, $dateTime);
            if ($updateTagBotUserResult == 'update-tag-bot-user-error') {
                return response()->json(['status' => $updateTagBotUserResult], 500);
            }

            return response()->json(['tag-bot-user' => $updateTagBotUserResult], 200);
        }
    }

    function blockedByBotUser($botId, $botUserId, $dateTime)
    {
        try {
            BotUserTag::where('bot_id', $botId)
                ->where('bot_user_id', $botUserId)
                ->update([
                    'kick_status' => 1
                ]);

            Log::debug('bot is blocked by user.');
            return 'success';
        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'blocked-by-bot-user-error';
        }
    }

    private function tagNewBotUser($botId, $botUserId, $dateTime)
    {
        try {
            $result = BotUserTag::create([
                'bot_id' => $botId,
                'bot_user_id' => $botUserId,
                'first_engage' => $dateTime,
                'last_engage' => $dateTime,
                'reply_status' => 0,
                'kick_status' => 0,
                'status' => 0,
                'created_at' => $dateTime,
                'updated_at' => $dateTime
            ]);

            Log::debug('new bot user tagged.');
            return $result['id'];

        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'tag-new-bot-user-error';
        }
    }

    private function updateTagBotUser($botId, $botUserId, $dateTime)
    {
        try {
            $result = BotUserTag::where('bot_id', $botId)
                ->where('bot_user_id', $botUserId)
                ->where('status', 0)
                ->first();

            $result->update([
                'reply_status' => 0,
                'kick_status' => 0,
                'last_engage' => $dateTime
            ]);

            Log::debug('bot user updated.');
            return $result['id'];
        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'update-tag-bot-user-error';
        }
    }
}
