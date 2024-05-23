<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Farm;
use Filament\Tables;
use App\Models\Farmer;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CultureSetting;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\FarmResource\Pages;


class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;
    protected static ?string $pluralModelLabel = 'Cultures';

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->live()
                    ->label('Famille'),
                Forms\Components\Select::make('culture_setting_id')
                    ->options(fn(Get $get) => CultureSetting::where('category_id', $get('category_id'))->pluck('name', 'id')->toArray())
                    ->relationship('cultureSetting', 'name')
                    ->label('Paramètres de Culture'),
                Forms\Components\Select::make('farmer_id')
                    ->options(fn(Get $get) => Farmer::all()->pluck('fullname', 'id')->toArray())
                    ->relationship('farmer', 'fullname')
                    ->required()
                    ->searchable()
                    ->live()
                    ->preload()
                    ->label('Agriculteur'),
                TextInput::make('code')
                    ->disabled()
                    ->required()
                    ->default('FARM0000' . Farm::count() + 1),
                Forms\Components\TextInput::make('area')
                    ->required()
                    ->numeric()
                    ->label('Superficie'),
                Select::make('unit_id')
                    ->label('Unite')
                    ->relationship('unit', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default('ha'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('farmer.fullname')
                    ->sortable()
                    ->label('Agriculteur'),
                Tables\Columns\TextColumn::make('farmer.address')
                    ->sortable()
                    ->label('Adresse'),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable()
                    ->label('Famille'),
                Tables\Columns\TextColumn::make('cultureSetting.name')
                    ->numeric()
                    ->sortable()
                    ->label('Paramètre de culture'),
                Tables\Columns\TextColumn::make('area')
                    ->numeric()
                    ->sortable()
                    ->label('Superficie'),
                Tables\Columns\TextColumn::make('unit.name')
                    ->searchable()
                    ->label('Unite'),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFarms::route('/'),
        ];
    }
}
