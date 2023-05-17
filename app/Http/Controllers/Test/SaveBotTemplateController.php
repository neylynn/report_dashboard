<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\BotTemplate;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaveBotTemplateController extends Controller
{
    // create bot flow via test route for development
    public function createBotTemplate(Request $request)
    {
        $botId = 2;
        $botflow = json_decode(file_get_contents(base_path('flow_samples.json')));
        $botflow = json_encode($botflow);

        $botTemplateCountResult = BotTemplate::where('bot_id', $botId)->get()->count();

        if ($botTemplateCountResult > 0) {
            $updateBotTemplateResult = $this->updateBotTemplate($botId, $botflow);
            return $updateBotTemplateResult;
        }

        try {
            $result = BotTemplate::create([
                'bot_id' => $botId,
                'flow' => $botflow
            ]);

            return $result;

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'bot-template-create-error';
        }
    }

    // update bot flow via test route for development when something changes in flow_samplesjson file
    private function updateBotTemplate($botId, $botflow)
    {
        try {
            $result = BotTemplate::where('bot_id', $botId)
                        ->update([
                            'flow' => $botflow
                        ]);

            return $result;

        } catch (QueryException $qe) {
            Log::error($qe->getMessage());
            return 'bot-template-update-error';
        }
    }
}
