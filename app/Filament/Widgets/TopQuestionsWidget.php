<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use App\Models\ResponseAnswer;
use App\Models\Survey;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopQuestionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Performing Questions';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Question::query()
                    ->select('questions.*')
                    ->selectRaw('(
                        SELECT COALESCE(AVG(qo.score), 0)
                        FROM question_options qo
                        WHERE qo.question_id = questions.id
                    ) as avg_option_score')
                    ->selectRaw('(
                        SELECT COUNT(*)
                        FROM response_answers
                        WHERE question_id = questions.id
                    ) as answers_count')
                    ->join('question_types', 'questions.question_type_id', '=', 'question_types.id')
                    ->whereRaw('(
                        SELECT COUNT(*)
                        FROM response_answers
                        WHERE question_id = questions.id
                    ) > 0')
                    ->orderByRaw('avg_option_score DESC')
                    ->orderByRaw('answers_count DESC')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('survey.title')
                    ->label('Survey')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->limit(50),

                Tables\Columns\TextColumn::make('questionType.name')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('avg_option_score')
                    ->label('Avg Score')
                    ->numeric(2),

                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Answers')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Question $record): string => route('filament.admin.resources.questions.edit', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
