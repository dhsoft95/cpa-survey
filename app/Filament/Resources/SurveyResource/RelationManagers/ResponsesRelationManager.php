<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use App\Exports\SurveyResponsesExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Carbon;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $recordTitleAttribute = 'completion_code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Response Details')
                    ->schema([
                        Forms\Components\TextInput::make('completion_code')
                            ->label('Completion Code')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->disabled(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),

                        Forms\Components\Toggle::make('is_winner')
                            ->label('Winner')
                            ->helperText('Mark this response as a winner for gift card'),
                    ])
                    ->columns(2),

                // Basic demographic data display
                Forms\Components\Section::make('Demographic Data')
                    ->schema([
                        Forms\Components\TextInput::make('demographic_data.is_cpa_member')
                            ->label('CPA Member')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.birth_year')
                            ->label('Birth Year')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.gender')
                            ->label('Gender')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.provincial_cpa_body')
                            ->label('Provincial CPA Body')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.years_designation')
                            ->label('Years Since Designation')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.job_title')
                            ->label('Job Title')
                            ->disabled(),

                        Forms\Components\TextInput::make('demographic_data.yearly_compensation')
                            ->label('Yearly Compensation')
                            ->prefix('$')
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // EI Scores display
                Forms\Components\Section::make('Emotional Intelligence Scores')
                    ->schema([
                        Forms\Components\Placeholder::make('total_score')
                            ->label('Total EI Score')
                            ->content(function ($record) {
                                return $record->total_score ?? 'Not calculated';
                            }),

                        Forms\Components\Placeholder::make('esa_score')
                            ->label('Emotional Self-Awareness')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['esa']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('ee_score')
                            ->label('Emotional Expression')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['ee']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('eao_score')
                            ->label('Emotional Awareness of Others')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['eao']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('er_score')
                            ->label('Emotional Reasoning')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['er']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('esm_score')
                            ->label('Emotional Self-Management')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['esm']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('emo_score')
                            ->label('Emotional Management of Others')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['emo']['raw'] ?? 'N/A';
                            }),

                        Forms\Components\Placeholder::make('esc_score')
                            ->label('Emotional Self-Control')
                            ->content(function ($record) {
                                return $record->demographic_data['ei_scores']['esc']['raw'] ?? 'N/A';
                            }),
                    ])
                    ->columns(2),

                // Visual score indicator using Markdown - will show a bar chart of EI scores
                Forms\Components\Section::make('Score Visualization')
                    ->schema([
                        Forms\Components\View::make('filament.components.ei-score-chart')
                            ->viewData(function ($record) {
                                // Prepare data for the chart
                                $scores = [];
                                if (isset($record->demographic_data['ei_scores'])) {
                                    foreach ($record->demographic_data['ei_scores'] as $category => $data) {
                                        $scores[$category] = [
                                            'name' => match($category) {
                                                'esa' => 'Self-Awareness',
                                                'ee' => 'Expression',
                                                'eao' => 'Awareness of Others',
                                                'er' => 'Reasoning',
                                                'esm' => 'Self-Management',
                                                'emo' => 'Management of Others',
                                                'esc' => 'Self-Control',
                                                default => $category,
                                            },
                                            'score' => $data['raw'] ?? 0,
                                            'max' => $data['max'] ?? 0,
                                            'percent' => $data['max'] > 0 ? round(($data['raw'] / $data['max']) * 100) : 0,
                                        ];
                                    }
                                }

                                return [
                                    'scores' => $scores,
                                    'totalScore' => $record->total_score ?? 0,
                                ];
                            }),
                    ])
                    ->collapsed(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('completion_code')
            ->columns([
                Tables\Columns\TextColumn::make('completion_code')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_winner')
                    ->label('Winner')
                    ->boolean()
                    ->trueIcon('heroicon-o-trophy')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('demographic_data.is_cpa_member')
                    ->label('CPA Member')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'yes' => 'success',
                        'no' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('demographic_data.job_title')
                    ->label('Job Title')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_score')
                    ->label('Total Score')
                    ->sortable()
                    ->tooltip('Total Emotional Intelligence Score')
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),

                Tables\Columns\TextColumn::make('answer_count')
                    ->label('Questions Answered')
                    ->tooltip('Number of questions answered')
                    ->state(function ($record) {
                        return $record->answers()->count();
                    }),

                // Display a sparkline-style chart of category scores
                Tables\Columns\TextColumn::make('category_scores')
                    ->label('EI Category Scores')
                    ->html()
                    ->state(function ($record) {
                        if (!isset($record->demographic_data['ei_scores'])) {
                            return '<span class="text-gray-400">No scores</span>';
                        }

                        $colors = [
                            'esa' => '#3B82F6', // blue
                            'ee' => '#8B5CF6',  // purple
                            'eao' => '#EC4899', // pink
                            'er' => '#10B981',  // green
                            'esm' => '#F59E0B', // amber
                            'emo' => '#EF4444', // red
                            'esc' => '#6366F1',  // indigo
                        ];

                        $bars = '';
                        foreach ($record->demographic_data['ei_scores'] as $category => $data) {
                            if (!isset($data['raw']) || !isset($data['max']) || $data['max'] == 0) {
                                continue;
                            }

                            $percent = round(($data['raw'] / $data['max']) * 100);
                            $tooltip = match($category) {
                                    'esa' => 'Self-Awareness',
                                    'ee' => 'Expression',
                                    'eao' => 'Awareness of Others',
                                    'er' => 'Reasoning',
                                    'esm' => 'Self-Management',
                                    'emo' => 'Management of Others',
                                    'esc' => 'Self-Control',
                                    default => $category,
                                } . ': ' . $data['raw'] . '/' . $data['max'];

                            $bars .= '<div title="' . $tooltip . '" class="h-2 w-8 rounded-full inline-block mx-0.5" style="background-color: ' . ($colors[$category] ?? '#888') . '; opacity: ' . ($percent / 100) . ';"></div>';
                        }

                        return '<div class="flex items-center">' . $bars . '</div>';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_winner')
                    ->label('Winner Status')
                    ->options([
                        '1' => 'Winners',
                        '0' => 'Non-Winners',
                    ]),

                Tables\Filters\SelectFilter::make('is_cpa')
                    ->label('CPA Member')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            return $query->whereJsonContains('demographic_data->is_cpa_member', $data['value']);
                        }
                        return $query;
                    }),

                Tables\Filters\Filter::make('has_scores')
                    ->label('Has EI Scores')
                    ->query(function (Builder $query) {
                        return $query->where('total_score', '>', 0);
                    })
                    ->toggle(),

                Tables\Filters\Filter::make('completed_range')
                    ->form([
                        Forms\Components\DatePicker::make('completed_from'),
                        Forms\Components\DatePicker::make('completed_until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['completed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '>=', $date),
                            )
                            ->when(
                                $data['completed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_answers')
                    ->label('View Answers')
                    ->icon('heroicon-o-list-bullet')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.survey-responses.view', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('toggle_winner')
                    ->label(fn ($record) => $record->is_winner ? 'Remove Winner' : 'Mark as Winner')
                    ->icon('heroicon-o-trophy')
                    ->color(fn ($record) => $record->is_winner ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_winner' => !$record->is_winner]);
                    }),

                Tables\Actions\Action::make('recalculate_scores')
                    ->label('Recalculate Scores')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $score = $record->calculateScore();
                        Notification::make()
                            ->title("Scores recalculated: {$score}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_winners')
                        ->label('Mark as Winners')
                        ->icon('heroicon-o-trophy')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_winner' => true]);
                            }
                        }),

                    Tables\Actions\BulkAction::make('recalculate_bulk_scores')
                        ->label('Recalculate Scores')
                        ->icon('heroicon-o-calculator')
                        ->color('info')
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->calculateScore();
                                $count++;
                            }

                            Notification::make()
                                ->title("Recalculated scores for {$count} responses")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('completed_at', 'desc')
            ->headerActions([
                Tables\Actions\Action::make('export_all')
                    ->label('Export All Responses')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $surveyId = $this->getOwnerRecord()->id;
                        return (new SurveyResponsesExport($surveyId))->map('survey_responses.xlsx');
                    }),

                Tables\Actions\Action::make('analytics')
                    ->label('View Analytics')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->url(function () {
                        $surveyId = $this->getOwnerRecord()->id;
                        return route('surveys.analytics', $surveyId);
                    })
                    ->openUrlInNewTab(),
            ]);
    }
}
