<?php

namespace App\TelegramServiceProviders\Interfaces;

interface TelegramServiceInterface
{
    public function getUpdates($update, $telegramBotId);
}
