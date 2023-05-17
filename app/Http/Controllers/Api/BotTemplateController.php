<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TelegramService\BotFlowInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class BotTemplateController extends Controller
{
    use BotFlowInfo;
    public function getBotFlowData(Request $request)
    {
        $botId = $request->bot_id;
        $getBotFlowResult = $this->getBotFlow($botId);
        if ($getBotFlowResult == 'get-bot-flow-error') {
            return response()->json(['status' => $getBotFlowResult], 500);
        }

        // get bot flow by json text type and decode to change to array type
        $botFlow = $getBotFlowResult->flow;
        $botFlow = json_decode($botFlow, true);

        return response()->json([
            'bot_flow' => $botFlow
        ], 200);
    }

    public function saveBotFlowData(Request $request)
    {
        $botId = $request->bot_id;
        $botFlow = $request->bot_flow;

        foreach($botFlow['data'] as $messageKey => $messageData) {
            if (array_key_exists('file_content', $messageData) && array_key_exists('photo', $messageData)) {
                $fileContent = $messageData['file_content'];

                if ($fileContent == '') {
                    return response()->json([
                        'status' => 'no_file_content_error'
                    ], 200);
                }

                $fileName = time().'-'.$fileContent->getClientOriginalName();
                $fileExtension = $fileContent->extension();
                $fileContentType = $fileContent->getClientMimeType();

                if (($fileExtension == 'jpg' && $fileContentType == 'image/jpeg') || ($fileExtension == 'png' && $fileContentType == 'image/png')) {
                    $fileContent->move(storage_path('app/public/img/'), $fileName);
                    $botFlow['data'][$messageKey]['photo'] = url('').'/storage/img/'.$fileName;
                    unset($botFlow['data'][$messageKey]['file_content']);
                } else {
                    return response()->json([
                        'status' => 'photo_upload_error'
                    ], 200);
                }

            } elseif (array_key_exists('file_content', $messageData) && array_key_exists('animation', $messageData)) {
                $fileContent = $messageData['file_content'];

                if ($fileContent == '') {
                    return response()->json([
                        'status' => 'no_file_content_error'
                    ], 200);
                }

                $fileName = time().'-'.$fileContent->getClientOriginalName();
                $fileExtension = $fileContent->extension();
                $fileContentType = $fileContent->getClientMimeType();

                if (($fileExtension == 'gif' && $fileContentType == 'image/gif')) {
                    $fileContent->move(storage_path('app/public/img'), $fileName);
                    $botFlow['data'][$messageKey]['animation'] = url('').'/storage/img/'.$fileName;
                    unset($botFlow['data'][$messageKey]['file_content']);
                } else {
                    return response()->json([
                        'status' => 'gif_upload_error'
                    ], 200);
                }

            }


            if (array_key_exists('reply_markup', $messageData)) {
                if (array_key_exists('resize_keyboard', $messageData['reply_markup'])) {
                    $messageData['reply_markup']['resize_keyboard'] = true;
                    $botFlow['data'][$messageKey]['reply_markup']['resize_keyboard'] = true;
                }

                if (array_key_exists('one_time_keyboard', $messageData['reply_markup'])) {
                    $botFlow['data'][$messageKey]['reply_markup']['one_time_keyboard'] = true;
                }
            }
        }

        Log::info($botFlow);
        $saveBotFlowResult = $this->saveBotFlow($botId, $botFlow);

        return response()->json([
            'status' => $saveBotFlowResult
        ], 200);

        // return response()->json([
        //     'status' => 1
        // ], 200);

    }
}
