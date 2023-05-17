<?php

namespace App\TelegramServiceProviders\Services;

use App\TelegramServiceProviders\Interfaces\TelegramServiceInterface;
use Illuminate\Support\Facades\Log;

class CoffeeMusicCodingService implements TelegramServiceInterface
{

    public function getUpdates($update, $telegramBotId)
    {
        Log::info('Message arrive to Coffee Music Coding Service');
    }

}
