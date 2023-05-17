<?php

namespace Database\Seeders;

use App\Models\Bot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bot::create([
            'user_id' => 2,
            'telegram_bot_id' => 5486475799,
            'name' => 'Demo Bot',
            'user_name' => 'bp_demo_bot',
            'api_token' => '5486475799:AAF5wq108Ajd2Ul7M0kwQYZrMb5vIepyZ0Y',
            'image' => 'undraw_rocket.svg',
            'status' => 0
        ]);

        Bot::create([
            'user_id' => 3,
            'telegram_bot_id' => 5401059461,
            'name' => 'Aung Nai Oo',
            'user_name' => 'aung_nai_oo_bot',
            'api_token' => '5401059461:AAHSf7m-JW_SoGDcB3gNocXmAOd2UhVhD6A',
            'image' => 'undraw_rocket.svg',
            'status' => 0
        ]);

        Bot::create([
            'user_id' => 4,
            'telegram_bot_id' => 5430895911,
            'name' => 'Coffee, Music & CodinG',
            'user_name' => 'coffee_music_coding_bot',
            'api_token' => '5430895911:AAGMZuFQxd7Lo-i1hPDbYpI4aNQLJfbzMbE',
            'image' => 'undraw_rocket.svg',
            'status' => 0
        ]);
    }
}
