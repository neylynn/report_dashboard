<?php

namespace App\TelegramServiceProviders\Services;

use App\TelegramServiceProviders\Interfaces\TelegramServiceInterface;
use App\Traits\TelegramService\GetBotInfo;
use App\Traits\TelegramService\GetBotUserInfo;
use App\Traits\TelegramService\ReplyMessage;
use App\Traits\TelegramService\SaveBotUser;
use App\Traits\TelegramService\TagBotUser;
use App\Traits\Utilities\DateTimeFormatter;
use Illuminate\Support\Facades\Log;

class DefaultService implements TelegramServiceInterface
{
    use GetBotInfo;
    use GetBotUserInfo;
    use DateTimeFormatter;
    use SaveBotUser;
    use ReplyMessage;
    use TagBotUser;

    public function getUpdates($update, $telegramBotId)
    {

        Log::info($update);

        // get bot id by telegram bot id
        $getBotDatasByTelegramBotIdResult = $this->getBotDatasByTelegramBotId($telegramBotId);
        if ($getBotDatasByTelegramBotIdResult == 'get_bot_datas_error') {
            return response()->json(['status' => $getBotDatasByTelegramBotIdResult], 500);
        }

        if (!isset($getBotDatasByTelegramBotIdResult->id)) {
            return;
        }

        $botId = $getBotDatasByTelegramBotIdResult->id;
        $botToken = $getBotDatasByTelegramBotIdResult->api_token;

        // check if the incoming update is a message or not
        if (isset($update->message)) {
            // check if the incoming message is private to bot chat or not
            if ($update['message']['chat']['type'] == 'private' && $update['message']['from']['id'] == $update['message']['chat']['id']) {

                $telegramUserId = $update['message']['from']['id'];
                $firstName = $update['message']['from']['first_name'];
                $lastName = isset($update['message']['from']['last_name'])? $update['message']['from']['last_name'] : '';
                $name = isset($update['message']['from']['last_name'])? $firstName.' '.$lastName : $firstName;
                $userName = isset($update['message']['from']['username'])? $update['message']['from']['username'] : null;
                $dateTimeStamp = $update['message']['date'];
                $dateTime = $this->timeStampToDateTime($dateTimeStamp);

                // Log::info([$telegramBotId, $telegramUserId, $name, $userName, $dateTime]);

                // check the message type is text or not
                if (isset($update['message']['text'])) {
                    $textMessage = $update['message']['text'];

                    $botUserId = $this->saveBotUser($botId, $telegramUserId, $name, $userName, $dateTime);
                    $this->autoReplyMessage($botId, $botToken, $telegramUserId, $botUserId, $textMessage);
                }
            }
        }

        // ban unban status checker
        if (isset($update->my_chat_member)) {
            if (isset($update['my_chat_member']['old_chat_member']['status']) && isset($update['my_chat_member']['new_chat_member']['status'])) {
                // check if the bot got baned by bot user
                if ($update['my_chat_member']['old_chat_member']['status'] == 'member' && $update['my_chat_member']['new_chat_member']['status'] == 'kicked') {

                    $telegramUserId = $update['my_chat_member']['from']['id'];
                    // trait GetBotUserInfo [to get bot user id by telegram user id]
                    $telegramUserIdToBotUserIdResult = $this->telegramUserIdToBotUserId($telegramUserId);
                    if ($telegramUserIdToBotUserIdResult == 'get_bot_user_id_error') {
                        return response()->json(['status' => $telegramUserIdToBotUserIdResult], 500);
                    }
                    $botUserId = $telegramUserIdToBotUserIdResult->id;

                    $dateTimeStamp = $update['my_chat_member']['date'];
                    $dateTime = $this->timeStampToDateTime($dateTimeStamp);

                    if ($update['my_chat_member']['new_chat_member']['user']['id'] == $telegramBotId) {
                        // blocked by user
                        $blockedByBotUserResult = $this->blockedByBotUser($botId, $botUserId, $dateTime);
                        if ($blockedByBotUserResult == 'blocked-by-bot-user-error') {
                            return response()->json(['status' => $blockedByBotUserResult], 500);
                        }
                    }
                }

            }
        }
    }
}
