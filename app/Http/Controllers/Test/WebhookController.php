<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    private $ngrokUrl = 'https://9b26-8-30-234-205.ngrok.io';
    // private $ngrokUrl = 'https://9fc0-8-29-105-31.ngrok.io';

    public function setWebhook(Request $request)
    {

        $url = $this->ngrokUrl;

        $response = Http::post('https://api.telegram.org/bot5486475799:AAF5wq108Ajd2Ul7M0kwQYZrMb5vIepyZ0Y/setWebhook', [
            'url' => $url.'/api/5486475799',
        ]);

        // $response = Http::post('https://api.telegram.org/bot5478866245:AAFoS3PiSYjTaRA0Pn4XlVvqyShPh58sfkQ/setWebhook', [
        //     'url' => $url.'/api/5478866245',
        // ]);

        $response2 = Http::post('https://api.telegram.org/bot5401059461:AAHSf7m-JW_SoGDcB3gNocXmAOd2UhVhD6A/setWebhook', [
            'url' => $url.'/api/5401059461',
        ]);

        $response3 = Http::post('https://api.telegram.org/bot5430895911:AAGMZuFQxd7Lo-i1hPDbYpI4aNQLJfbzMbE/setWebhook', [
            'url' => $url.'/api/5430895911',
        ]);

        Log::info($response);
        Log::info($response2);
        Log::info($response3);

        return response('success', 200);
    }

    public function deleteWebhook(Request $request)
    {

        $response = Http::post('https://api.telegram.org/bot5486475799:AAF5wq108Ajd2Ul7M0kwQYZrMb5vIepyZ0Y/setWebhook', [
            'url' => '',
        ]);

        $response2 = Http::post('https://api.telegram.org/bot5401059461:AAHSf7m-JW_SoGDcB3gNocXmAOd2UhVhD6A/setWebhook', [
            'url' => '',
        ]);

        $response3 = Http::post('https://api.telegram.org/bot5430895911:AAGMZuFQxd7Lo-i1hPDbYpI4aNQLJfbzMbE/setWebhook', [
            'url' => '',
        ]);

        Log::info($response);
        Log::info($response2);
        Log::info($response3);

        return response('success', 200);
    }
}
