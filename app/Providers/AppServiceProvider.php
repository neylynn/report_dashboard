<?php

namespace App\Providers;

use App\TelegramServiceProviders\Services\CoffeeMusicCodingService;
use App\TelegramServiceProviders\Services\DefaultService;
use App\TelegramServiceProviders\TelegramServiceUpdate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TelegramServiceUpdate::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $services = [
            'default' => new DefaultService(),
            'coffee_music_coding_bot' => new CoffeeMusicCodingService()
        ];

        foreach ($services as $service_name => $repository) {
            $this->app->make(TelegramServiceUpdate::class)
                ->register("$service_name", $repository);

        }

    }
}
