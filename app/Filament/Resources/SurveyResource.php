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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Survey Management';

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

                        // Added winners count field
                        Forms\Components\TextInput::make('winners_count')
                            ->label('Number of Winners')
                            ->helperText('Number of top-scoring respondents to mark as winners')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(100),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

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
                    ->sortable(),

                Tables\Columns\TextColumn::make('winners_count')
                    ->label('Winners')
                    ->counts('responses', function($query) {
                        return $query->where('is_winner', true);
                    })
                    ->sortable(),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
//                    ->url(fn (Survey $record): string => route('surveys.show', $record))
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab(),
                // Add automatic winner selection action
                Tables\Actions\Action::make('select_winners')
                    ->label('Select Winners')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Select Top Winners')
                    ->modalDescription(fn (Survey $survey) => "This will automatically select the top {$survey->winners_count} respondents based on their scores and mark them as winners.")
                    ->form([
                        Forms\Components\TextInput::make('winners_count')
                            ->label('Number of Winners')
                            ->default(fn (Survey $survey) => $survey->winners_count ?? 3)
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->action(function (Survey $survey, array $data): void {
                        $count = $data['winners_count'] ?? $survey->winners_count ?? 3;

                        // Save the winners count to the survey
                        $survey->update(['winners_count' => $count]);

                        // Select top winners
                        $winners = SurveyResponse::selectTopWinners($survey->id, $count);

                        // Display notification
                        Notification::make()
                            ->title("Selected {$winners->count()} winners")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
