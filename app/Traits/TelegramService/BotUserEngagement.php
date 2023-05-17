<?php

namespace App\Traits\TelegramService;

use App\Models\UserEngagement;
use App\Traits\Utilities\DateTimeFormatter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait BotUserEngagement
{
    use DateTimeFormatter;

    function engageUser($botUserTagId, $dateTime)
    {
        $date = $this->dateTimeToDate($dateTime);
        $checkUserEngagementResult = $this->checkUserEngagement($botUserTagId, $date);
        Log::info($checkUserEngagementResult);

        if (!$checkUserEngagementResult) {
            $this->addEngagement($botUserTagId, $date);
        } else {
            $this->updateEngagement($checkUserEngagementResult, $checkUserEngagementResult->engage_count);
        }
    }

    private function checkUserEngagement($botUserTagId, $date)
    {
        $result = UserEngagement::where('bot_user_tag_id', $botUserTagId)
                    ->where('engage_date', $date)
                    ->first();

        return $result;
    }

    private function addEngagement($botUserTagId, $date)
    {
        try {
            UserEngagement::create([
                'bot_user_tag_id' => $botUserTagId,
                'engage_count' => 1,
                'engage_date' => $date
            ]);

        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'add-engagement-error';
        }
    }

    private function updateEngagement($checkUserEngagementResult, $engageCount)
    {
        try {
            $checkUserEngagementResult->update([
                'engage_count' => $engageCount+1
            ]);
        } catch (QueryException $qe) {

            Log::error($qe->getMessage());
            return 'update-engagement-error';
        }
    }

}
