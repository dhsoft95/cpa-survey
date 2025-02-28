<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use App\Models\QuestionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Survey Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_id')
                    ->relationship('survey', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

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
                    ->default(function () {
                        $record = request()->route('record');

                        if ($record) {
                            return $record->order;
                        }

                        $surveyId = request('survey_id') ?? request('data.survey_id');

                        if ($surveyId) {
                            return Question::where('survey_id', $surveyId)->count() + 1;
                        }

                        return 1;
                    }),

                Forms\Components\KeyValue::make('settings')
                    ->label('Advanced Settings')
                    ->keyLabel('Setting')
                    ->valueLabel('Value')
                    ->columnSpanFull(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('survey.title')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('questionType.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('options_count')
                    ->counts('options')
                    ->label('Options'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('survey')
                    ->relationship('survey', 'title'),
                Tables\Filters\SelectFilter::make('question_type')
                    ->relationship('questionType', 'name'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
