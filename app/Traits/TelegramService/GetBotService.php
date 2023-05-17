<?php

namespace App\Traits\TelegramService;

trait GetBotService
{
    function getService($telegramBotId)
    {
        $botServices = [
            '5430895911' => 'coffee_music_coding_bot'
        ];

        if (array_key_exists($telegramBotId, $botServices)) {
            return $botServices[$telegramBotId];
        } else {
            return 'default';
        }
    }
}
