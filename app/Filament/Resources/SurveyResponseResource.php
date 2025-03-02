<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResponseResource\Pages;
use App\Filament\Resources\SurveyResponseResource\RelationManagers;
use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Survey Management';

    protected static ?string $navigationLabel = 'Responses';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_id')
                    ->relationship('survey', 'title')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('completion_code')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\TextInput::make('total_score')
                    ->label('Total Score')
                    ->numeric()
                    ->disabled(),

                Forms\Components\Toggle::make('is_winner')
                    ->label('Mark as Winner'),

                Forms\Components\DateTimePicker::make('completed_at')
                    ->disabled(),

                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\TextInput::make('user_agent')
                    ->maxLength(255)
                    ->disabled()
                    ->columnSpanFull(),

                Forms\Components\KeyValue::make('demographic_data')
                    ->columnSpanFull()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('survey.title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completion_code')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('total_score')
                    ->label('Score')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_winner')
                    ->label('Winner')
                    ->boolean(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('total_score', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('survey')
                    ->relationship('survey', 'title'),

                Tables\Filters\TernaryFilter::make('is_winner')
                    ->label('Winners only'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_winner')
                    ->label(fn ($record) => $record->is_winner ? 'Remove Winner' : 'Mark as Winner')
                    ->icon(fn ($record) => $record->is_winner ? 'heroicon-o-x-circle' : 'heroicon-o-trophy')
                    ->color(fn ($record) => $record->is_winner ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_winner' => !$record->is_winner]);
                    }),
                // Add action to calculate/recalculate score
                Tables\Actions\Action::make('calculate_score')
                    ->label('Calculate Score')
                    ->icon('heroicon-o-calculator')
                    ->color('warning')
                    ->action(function (SurveyResponse $record) {
                        $score = $record->calculateScore();
                        Notification::make()
                            ->title("Score calculated: {$score}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_as_winners')
                        ->label('Mark as Winners')
                        ->icon('heroicon-o-trophy')
                        ->action(fn ($records) => $records->each->update(['is_winner' => true])),
                    Tables\Actions\BulkAction::make('remove_as_winners')
                        ->label('Remove as Winners')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_winner' => false])),
                    Tables\Actions\BulkAction::make('calculate_scores')
                        ->label('Calculate Scores')
                        ->icon('heroicon-o-calculator')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->calculateScore();
                            }
                            Notification::make()
                                ->title("Calculated scores for {$records->count()} responses")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyResponses::route('/'),
            'create' => Pages\CreateSurveyResponse::route('/create'),
            'edit' => Pages\EditSurveyResponse::route('/{record}/edit'),
        ];
    }
}
