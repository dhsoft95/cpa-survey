<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;


use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('completion_code')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\DateTimePicker::make('completed_at')
                    ->disabled(),

                Forms\Components\Toggle::make('is_winner')
                    ->label('Winner'),

                Forms\Components\TextInput::make('ip_address')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('completion_code')
            ->columns([
                Tables\Columns\TextColumn::make('completion_code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_winner')
                    ->label('Winner')
                    ->boolean(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_winner')
                    ->label('Winners only'),
            ])
            ->headerActions([
                // No create action for responses
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
                ]),
            ]);
    }
}
