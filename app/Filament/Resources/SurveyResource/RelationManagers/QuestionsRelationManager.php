<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use App\Models\QuestionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_type_id')
                    ->label('Question Type')
                    ->options(QuestionType::pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('settings', null)),

                Forms\Components\Textarea::make('question_text')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('help_text')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),

                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(fn ($livewire) => $livewire->ownerRecord->questions()->count() + 1),

                Forms\Components\Section::make('Question Options')
                    ->schema([
                        Forms\Components\Repeater::make('options')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('option_text')
                                    ->required(),
                                Forms\Components\TextInput::make('order')
                                    ->numeric()
                                    ->default(fn ($get) => $get('../../options') ? count($get('../../options')) + 1 : 1),
                                Forms\Components\TextInput::make('score')
                                    ->numeric()
                                    ->nullable(),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->defaultItems(5)
                            ->visible(fn (callable $get) => in_array(
                                QuestionType::find($get('question_type_id'))?->slug,
                                ['multiple-choice', 'checkbox', 'dropdown', 'likert-scale']
                            )),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->limit(50),

                Tables\Columns\TextColumn::make('questionType.name')
                    ->label('Type'),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean(),

                Tables\Columns\TextColumn::make('options_count')
                    ->counts('options')
                    ->label('Options'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
