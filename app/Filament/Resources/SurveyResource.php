<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use App\Exports\SurveyResponsesExport;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Survey Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Survey Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Start Date/Time'),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('End Date/Time')
                            ->after('starts_at'),

                        // Enhanced winners count field with better context
                        Forms\Components\TextInput::make('winners_count')
                            ->label('Number of Winners')
                            ->helperText('Number of top-scoring respondents to mark as winners for gift cards')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(100),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('EI Scoring Configuration')
                    ->schema([
                        Forms\Components\Toggle::make('use_reverse_scoring')
                            ->label('Use Reverse Scoring')
                            ->helperText('When enabled, negatively keyed items will be reverse scored')
                            ->default(true),

                        Forms\Components\ToggleButtons::make('winner_selection_method')
                            ->label('Winner Selection Method')
                            ->options([
                                'total_score' => 'Highest Total Score',
                                'random' => 'Random Selection',
                                'completion_time' => 'Earliest Completion',
                            ])
                            ->default('total_score'),

                        Forms\Components\TextInput::make('min_questions_answered')
                            ->label('Minimum Questions Threshold')
                            ->helperText('Minimum number of EI questions that must be answered for a valid score')
                            ->numeric()
                            ->default(20)
                            ->minValue(1)
                            ->maxValue(70),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('responses_count')
                    ->label('Responses')
                    ->counts('responses')
                    ->sortable()
                    ->color('success'),

                Tables\Columns\TextColumn::make('cpa_responses_count')
                    ->label('CPA Members')
                    ->tooltip('Number of responses from CPA members')
                    ->counts('responses', function ($query) {
                        return $query->whereJsonContains('demographic_data->is_cpa_member', 'yes');
                    })
                    ->color('primary'),

                Tables\Columns\TextColumn::make('winners_count')
                    ->label('Winners')
                    ->counts('responses', function($query) {
                        return $query->where('is_winner', true);
                    })
                    ->sortable()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('avg_total_score')
                    ->label('Avg EI Score')
                    ->tooltip('Average total EI score for all responses')
                    ->state(function (Survey $record): ?string {
                        $avg = $record->responses()
                            ->whereNotNull('total_score')
                            ->where('total_score', '>', 0)
                            ->avg('total_score');
                        return $avg ? number_format($avg, 1) : null;
                    })
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value']) {
                            'active' => $query->where('is_active', true),
                            'inactive' => $query->where('is_active', false),
                            default => $query,
                        };
                    }),

                Tables\Filters\Filter::make('has_responses')
                    ->label('Has Responses')
                    ->query(fn (Builder $query): Builder => $query->has('responses'))
                    ->toggle(),

                Tables\Filters\Filter::make('has_winners')
                    ->label('Has Winners')
                    ->query(fn (Builder $query): Builder => $query->whereHas('responses', function ($query) {
                        $query->where('is_winner', true);
                    }))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('preview')
                    ->url(fn (Survey $record): string => route('surveys.show', $record))
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->color('gray'),

                // Enhanced winner selection action
                Tables\Actions\Action::make('select_winners')
                    ->label('Select Winners')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Select Top Winners')
                    ->modalDescription(fn (Survey $survey) => "This will automatically select the top {$survey->winners_count} respondents based on their scores and mark them as winners for gift cards.")
                    ->form([
                        Forms\Components\TextInput::make('winners_count')
                            ->label('Number of Winners')
                            ->default(fn (Survey $survey) => $survey->winners_count ?? 3)
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        Forms\Components\Select::make('selection_method')
                            ->label('Selection Method')
                            ->options([
                                'total_score' => 'Highest Total Score',
                                'random' => 'Random Selection',
                                'completion_time' => 'Earliest Completion',
                            ])
                            ->default('total_score')
                            ->required(),

                        Forms\Components\Toggle::make('require_cpa')
                            ->label('CPA Members Only')
                            ->helperText('Only select winners who are CPA members')
                            ->default(true),

                        Forms\Components\Toggle::make('min_questions')
                            ->label('Apply Minimum Questions Threshold')
                            ->helperText('Only select winners who have answered a minimum number of questions')
                            ->default(true),
                    ])
                    ->action(function (Survey $survey, array $data): void {
                        $count = $data['winners_count'] ?? $survey->winners_count ?? 3;
                        $method = $data['selection_method'] ?? 'total_score';
                        $requireCpa = $data['require_cpa'] ?? true;
                        $minQuestions = $data['min_questions'] ?? true;

                        // Save the winners count to the survey
                        $survey->update(['winners_count' => $count]);

                        // Select winners based on method and criteria
                        $query = SurveyResponse::query()
                            ->where('survey_id', $survey->id)
                            ->whereNotNull('completed_at');

                        // Apply CPA filter if required
                        if ($requireCpa) {
                            $query->whereJsonContains('demographic_data->is_cpa_member', 'yes');
                        }

                        // Apply minimum questions threshold if required
                        if ($minQuestions && isset($survey->min_questions_answered)) {
                            $query->whereRaw("JSON_LENGTH(JSON_EXTRACT(demographic_data, '$.ei_scores')) >= ?", [$survey->min_questions_answered]);
                        }

                        // Apply sorting based on method
                        switch ($method) {
                            case 'random':
                                $query->inRandomOrder();
                                break;
                            case 'completion_time':
                                $query->orderBy('completed_at');
                                break;
                            case 'total_score':
                            default:
                                $query->orderByDesc('total_score');
                                break;
                        }

                        // Get winners
                        $winners = $query->limit($count)->get();

                        // Mark these as winners
                        foreach ($winners as $winner) {
                            $winner->update(['is_winner' => true]);
                        }

                        // Unmark any previous winners that are not in this set
                        SurveyResponse::where('survey_id', $survey->id)
                            ->where('is_winner', true)
                            ->whereNotIn('id', $winners->pluck('id'))
                            ->update(['is_winner' => false]);

                        // Display notification
                        Notification::make()
                            ->title("Selected {$winners->count()} winners using {$method} method")
                            ->success()
                            ->send();
                    }),

                // Export data action
                Tables\Actions\Action::make('export_data')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function (Survey $survey) {
                        return (new SurveyResponsesExport($survey->id))->download('survey_responses.xlsx');
                    }),

                // View EI Score Analytics
                Tables\Actions\Action::make('view_analytics')
                    ->label('EI Analytics')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn (Survey $record) => route('surveys.analytics', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggle_active')
                        ->label('Toggle Active Status')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
            RelationManagers\ResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
//            'view' => Pages\ViewSurvey::route('/{record}'),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Response Count' => $record->responses()->count(),
            'Active' => $record->is_active ? 'Yes' : 'No',
        ];
    }
}
