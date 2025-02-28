<?php
// app/Services/WinnerSelectionService.php
namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Log;

class WinnerSelectionService
{
    /**
     * Randomly select winners from survey responses
     *
     * @param Survey $survey The survey to select winners from
     * @param int $count Number of winners to select
     * @return array Array of selected winner survey responses
     */
    public function selectRandomWinners(Survey $survey, int $count = 1): array
    {
        try {
            // Get completed responses that aren't already winners
            $eligibleResponses = SurveyResponse::where('survey_id', $survey->id)
                ->whereNotNull('completed_at')
                ->where('is_winner', false)
                ->get();

            if ($eligibleResponses->isEmpty()) {
                Log::info("No eligible responses found for survey #{$survey->id}");
                return [];
            }

            // If we have fewer eligible responses than requested winners, adjust count
            $count = min($count, $eligibleResponses->count());

            // Randomly select winners
            $winners = $eligibleResponses->random($count);

            // Mark selected responses as winners
            foreach ($winners as $winner) {
                $winner->update(['is_winner' => true]);
            }

            Log::info("Selected {$count} winners for survey #{$survey->id}");
            return $winners->toArray();
        } catch (\Exception $e) {
            Log::error("Error selecting winners: {$e->getMessage()}");
            return [];
        }
    }
}
