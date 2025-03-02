<?php

namespace App\Filament\Widgets;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $activeSurveys = Survey::where('is_active', true)->count();
        $totalResponses = SurveyResponse::count();
        $completedResponses = SurveyResponse::whereNotNull('completed_at')->count();
        $completionRate = $totalResponses > 0
            ? round(($completedResponses / $totalResponses) * 100, 1)
            : 0;

        return [
            Stat::make('Active Surveys', $activeSurveys)
                ->description('Currently running surveys')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success'),

            Stat::make('Total Responses', $totalResponses)
                ->description('All survey responses')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description($completedResponses . ' completed responses')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate > 75 ? 'success' : ($completionRate > 50 ? 'warning' : 'danger')),
        ];
    }
}
