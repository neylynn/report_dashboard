<?php

namespace App\TelegramServiceProviders;

use App\TelegramServiceProviders\Interfaces\TelegramServiceInterface;

class TelegramServiceUpdate
{
    private $bots = [];

    function register($serviceName, TelegramServiceInterface $instance)
    {
        $this->bots[$serviceName] = $instance;
        return $this;
    }

    function set($serviceName)
    {

        if (array_key_exists($serviceName, $this->bots)) {
            return $this->bots[$serviceName];
        } else {
            throw new \Exception("Invalid Service");
        }
    }

}
