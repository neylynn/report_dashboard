<?php

namespace App\Traits\TelegramService;

use App\Models\BotTemplate;
use App\Models\BotUserTag;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ReplyMessage
{
    use BotFlowInfo;

    function autoReplyMessage($botId, $botToken, $telegramUserId, $botUserId, $messageOrQuery)
    {
        $getBotFlowResult = $this->getBotFlow($botId);
        if ($getBotFlowResult == 'get-bot-flow-error') {
            return response()->json(['status' => $getBotFlowResult], 500);
        }

        // get bot flow by json text type and decode to change to array type
        $botFlow = $getBotFlowResult->flow;
        $botFlow = json_decode($botFlow, true);

        if (array_key_exists($messageOrQuery, $botFlow['data'])) {
            $data = $botFlow['data'][$messageOrQuery];
            $data['chat_id'] = $telegramUserId;

            if (array_key_exists('text', $data)) {
                $response = Http::post('https://api.telegram.org/bot'.$botToken.'/sendMessage', $data);
            } elseif (array_key_exists('photo', $data)) {
                $response = Http::post('https://api.telegram.org/bot'.$botToken.'/sendPhoto', $data);
            } elseif (array_key_exists('animation', $data)) {
                $response = Http::post('https://api.telegram.org/bot'.$botToken.'/sendAnimation', $data);
            }

            Log::info($response);

            // autoreply message is successfully sent
            if ($response['ok'] == true) {

                $dateTimeStamp = $response['result']['date'];
                $dateTime = $this->timeStampToDateTime($dateTimeStamp);

                // update reply status to 1 to check bot had replied to the user
                $updateReplyStatusResult = $this->updateReplyStatus($botId, $botUserId, $dateTime);
                if ($updateReplyStatusResult == 'update-reply-status-error') {
                    return response()->json(['status' => $updateReplyStatusResult], 500);
                }
            } else {
                Log::error('message not replied to user');
            }
        } else {
            // real time emit
        }

    }

    private function updateReplyStatus($botId, $botUserId, $dateTime) {
        try {
            BotUserTag::where('bot_id', $botId)
                ->where('bot_user_id', $botUserId)
                ->where('status', 0)
                ->update([
                    'reply_status' => 1,
                    'last_engage' => $dateTime
                ]);

            Log::debug('reply status updated.');
            return 'success';
        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'update-reply-status-error';
        }
    }
}
