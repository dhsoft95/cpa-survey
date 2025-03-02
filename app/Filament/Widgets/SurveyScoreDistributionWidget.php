<?php

namespace App\Filament\Widgets;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SurveyScoreDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Score Distribution';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        return Survey::pluck('title', 'id')->toArray();
    }

    protected function getData(): array
    {
        // Get survey ID from filter or use the first survey
        $surveyId = $this->filter;

        if (!$surveyId) {
            $survey = Survey::first();
            $surveyId = $survey ? $survey->id : null;
        }

        if (!$surveyId) {
            return [
                'datasets' => [
                    [
                        'label' => 'No surveys available',
                        'data' => [],
                        'backgroundColor' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        // Group scores into ranges
        $scoreRanges = [
            '0-20' => ['min' => 0, 'max' => 20, 'color' => 'rgba(239, 68, 68, 0.7)'], // Red
            '21-40' => ['min' => 21, 'max' => 40, 'color' => 'rgba(245, 158, 11, 0.7)'], // Amber
            '41-60' => ['min' => 41, 'max' => 60, 'color' => 'rgba(234, 179, 8, 0.7)'], // Yellow
            '61-80' => ['min' => 61, 'max' => 80, 'color' => 'rgba(34, 197, 94, 0.7)'], // Green
            '81-100' => ['min' => 81, 'max' => 100, 'color' => 'rgba(6, 182, 212, 0.7)'], // Cyan
            '100+' => ['min' => 101, 'max' => 999999, 'color' => 'rgba(79, 70, 229, 0.7)'], // Indigo
        ];

        $labels = array_keys($scoreRanges);
        $counts = array_fill_keys($labels, 0);
        $colors = array_column($scoreRanges, 'color');

        // Count responses in each score range
        $responses = SurveyResponse::where('survey_id', $surveyId)
            ->whereNotNull('completed_at')
            ->get();

        foreach ($responses as $response) {
            foreach ($scoreRanges as $range => $config) {
                if ($response->total_score >= $config['min'] && $response->total_score <= $config['max']) {
                    $counts[$range]++;
                    break;
                }
            }
        }

        // Remove empty ranges
        $filteredLabels = [];
        $filteredCounts = [];
        $filteredColors = [];

        foreach ($labels as $index => $label) {
            if ($counts[$label] > 0) {
                $filteredLabels[] = $label;
                $filteredCounts[] = $counts[$label];
                $filteredColors[] = $colors[$index];
            }
        }

        // If all ranges are empty, show empty chart
        if (empty($filteredLabels)) {
            $filteredLabels = ['No Data'];
            $filteredCounts = [0];
            $filteredColors = ['rgba(156, 163, 175, 0.7)']; // Gray
        }

        return [
            'datasets' => [
                [
                    'label' => 'Response Count',
                    'data' => $filteredCounts,
                    'backgroundColor' => $filteredColors,
                ],
            ],
            'labels' => $filteredLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
