<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestWinnersWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SurveyResponse::query()
                    ->where('is_winner', true)
                    ->whereNotNull('completed_at')
                    ->latest('updated_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('survey.title')
                    ->label('Survey')
                    ->searchable(),

                Tables\Columns\TextColumn::make('completion_code')
                    ->label('Winner Code')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('total_score')
                    ->label('Score')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (SurveyResponse $record): string => route('filament.admin.resources.survey-responses.edit', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
