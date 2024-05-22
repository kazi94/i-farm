<?php

namespace App\Filament\Resources\FarmerResource\RelationManagers;

use Filament\Forms;
use App\Models\Farm;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CultureSetting;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class FarmsRelationManager extends RelationManager
{
    protected static string $relationship = 'farms';

    protected static ?string $title = 'Cultures';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->default('FARM0000' . Farm::count() + 1)
                    ->required(),
                TextInput::make('area')
                    ->label('Superficie')
                    ->default(0)
                    ->required(),
                Select::make('unit_id')
                    ->label('Unité')
                    ->relationship('unit', 'name')
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Unité')
                            ->required(),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Unité')
                            ->required(),
                    ]),
                Select::make('category_id')
                    ->label('Famille')
                    ->relationship('category', 'name')
                    ->live()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Famille')
                            ->required(),
                    ])->editOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Famille')
                                ->required(),
                        ]),
                Select::make('culture_setting_id')
                    ->label('Paramètres de Culture')
                    ->options(fn(Get $get) => CultureSetting::where('category_id', $get('category_id'))->pluck('name', 'id')->toArray())
                    ->required()
                    ->relationship('cultureSetting', 'name', fn(Builder $query, Get $get) => $query->where('category_id', $get('category_id')))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Paramètres de Culture')
                            ->required(),
                        Select::make('category_id')
                            ->label('Famille')
                            ->options(fn(Get $get) => Category::all()->pluck('name', 'id'))
                            ->required()
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Paramètres de Culture')
                            ->required(),
                        Select::make('category_id')
                            ->label('Famille')
                            ->options(fn(Get $get) => Category::all()->pluck('name', 'id'))
                            ->required()
                    ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->numeric()
                    ->sortable()
                    ->label('Code'),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->label('Famille'),
                Tables\Columns\TextColumn::make('cultureSetting.name')
                    ->sortable()
                    ->label('Paramètres de culture'),
                Tables\Columns\TextColumn::make('area')
                    ->view('tables.columns.farm-area')
                    ->sortable()
                    ->label('Superficie'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
