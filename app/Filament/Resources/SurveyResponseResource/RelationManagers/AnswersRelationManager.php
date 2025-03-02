<?php

namespace App\Filament\Resources\SurveyResponseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\QuestionOption;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_id')
                    ->relationship('question', 'question_text')
                    ->required()
                    ->disabled(),

                Forms\Components\Textarea::make('answer_text')
                    ->columnSpanFull(),

                Forms\Components\KeyValue::make('selected_options_display')
                    ->label('Selected Options')
                    ->columnSpanFull()
                    ->disabled()
                    ->visible(function ($record) {
                        return !empty($record->selected_options);
                    })
                    ->afterStateHydrated(function ($state, $record) {
                        if (empty($record->selected_options)) {
                            return [];
                        }

                        $options = [];
                        foreach ($record->selected_options as $optionId) {
                            $option = QuestionOption::find($optionId);
                            if ($option) {
                                $options['Option ' . $option->order] = $option->option_text .
                                    ($option->score ? " (Score: {$option->score})" : '');
                            }
                        }

                        return $options;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('answer_text')
                    ->wrap()
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('selected_options_display')
                    ->label('Selected Options')
                    ->state(function ($record) {
                        if (empty($record->selected_options)) {
                            return '';
                        }

                        $displayText = [];
                        foreach ($record->selected_options as $optionId) {
                            $option = QuestionOption::find($optionId);
                            if ($option) {
                                $scoreText = $option->score ? " (Score: {$option->score})" : '';
                                $displayText[] = $option->option_text . $scoreText;
                            }
                        }

                        return implode(', ', $displayText);
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('calculated_score')
                    ->label('Score')
                    ->state(function ($record) {
                        $score = 0;

                        // For multiple choice questions
                        if (!empty($record->selected_options)) {
                            foreach ($record->selected_options as $optionId) {
                                $option = QuestionOption::find($optionId);
                                if ($option && is_numeric($option->score)) {
                                    $score += $option->score;
                                }
                            }
                        }

                        // For text questions
                        $question = $record->question;
                        if ($question && isset($question->settings['correct_answer']) &&
                            $record->answer_text === $question->settings['correct_answer'] &&
                            isset($question->settings['score'])) {
                            $score += (int)$question->settings['score'];
                        }

                        return $score > 0 ? $score : '-';
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('question')
                    ->relationship('question', 'question_text'),

                Tables\Filters\Filter::make('has_score')
                    ->label('Has Score')
                    ->query(function (Builder $query) {
                        // This is a placeholder as we can't directly filter on calculated states
                        // You would need custom logic in your controller to properly filter scores
                        return $query;
                    }),
            ])
            ->headerActions([
                // No need for create action as answers are created through the survey form
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('recalculate_parent_score')
                    ->label('Update Response Score')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function ($record) {
                        // Get the parent response and recalculate its score
                        $response = $record->surveyResponse;
                        if ($response) {
                            $response->calculateScore();
                        }
                    })
                    ->successNotificationTitle('Score recalculated successfully'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('recalculate_parent_scores')
                        ->label('Update Response Scores')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($records) {
                            // Get unique parent responses and recalculate scores
                            $responseIds = $records->pluck('survey_response_id')->unique();
                            foreach ($responseIds as $responseId) {
                                $response = \App\Models\SurveyResponse::find($responseId);
                                if ($response) {
                                    $response->calculateScore();
                                }
                            }
                        })
                        ->successNotificationTitle('Scores recalculated successfully'),
                ]),
            ]);
    }
}
