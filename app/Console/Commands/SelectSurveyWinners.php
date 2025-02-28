<?php
// app/Console/Commands/SelectSurveyWinners.php
namespace App\Console\Commands;

use App\Models\Survey;
use App\Services\WinnerSelectionService;
use Illuminate\Console\Command;

class SelectSurveyWinners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:select-winners {survey_id?} {--count=1 : Number of winners to select}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Select random winners from completed survey responses';

    /**
     * Execute the console command.
     */
    public function handle(WinnerSelectionService $winnerService)
    {
        $surveyId = $this->argument('survey_id');
        $count = (int) $this->option('count');

        if ($surveyId) {
            // Select winners for a specific survey
            $survey = Survey::find($surveyId);
            if (!$survey) {
                $this->error("Survey with ID {$surveyId} not found.");
                return 1;
            }

            $this->selectWinnersForSurvey($survey, $winnerService, $count);
        } else {
            // Select winners for all active surveys
            $surveys = Survey::where('is_active', true)->get();
            if ($surveys->isEmpty()) {
                $this->info("No active surveys found.");
                return 0;
            }

            foreach ($surveys as $survey) {
                $this->selectWinnersForSurvey($survey, $winnerService, $count);
            }
        }

        return 0;
    }

    /**
     * Select winners for a specific survey
     */
    private function selectWinnersForSurvey(Survey $survey, WinnerSelectionService $winnerService, int $count)
    {
        $this->info("Selecting {$count} winner(s) for survey: {$survey->title}");

        $winners = $winnerService->selectRandomWinners($survey, $count);

        if (empty($winners)) {
            $this->warn("No eligible responses found for this survey.");
            return;
        }

        $this->info("Selected " . count($winners) . " winner(s):");

        $this->table(
            ['ID', 'Completion Code', 'Completed At'],
            collect($winners)->map(function ($winner) {
                return [
                    $winner['id'],
                    $winner['completion_code'],
                    $winner['completed_at'],
                ];
            })
        );
    }
}
