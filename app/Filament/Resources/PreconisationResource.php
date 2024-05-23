<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Farm;
use Filament\Tables;
use App\Models\Farmer;
use App\Models\Intrant;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Preconisation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use App\Actions\PrintPreconisationAction;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PreconisationResource\Pages;
use App\Filament\Resources\PreconisationResource\RelationManagers;

class PreconisationResource extends Resource
{
    protected static ?string $model = Preconisation::class;

    protected static ?string $modelLabel = 'Préconisation';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('farmer_id')
                    ->label('Agriculteur')
                    ->options(Farmer::all()->pluck('fullname', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->relationship('farmer', 'fullname'),

                Forms\Components\Select::make('farm_id')
                    ->label('Culture')
                    ->hidden(fn(Get $get) => !$get('farmer_id'))
                    ->options(fn(Get $get) => Farm::where('farmer_id', $get('farmer_id'))->get()->pluck('code', 'id'))
                    ->required()
                    ->relationship('farm', 'code'),

                Forms\Components\DatePicker::make('date_preconisation')
                    ->label('Date de préconisation')
                    ->required(),

                Forms\Components\Repeater::make('preconisation_items')
                    ->label('Principes actifs')
                    ->schema([
                        Forms\Components\Select::make('intrant_id')
                            ->label('Intrant')
                            ->options(Intrant::all()->pluck('name_fr', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('intrant', 'name_fr'),

                        Forms\Components\TextInput::make('price')
                            ->label('Prix')
                            ->required()
                            ->numeric()
                            ->rules(['required', 'numeric', 'min:0']),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantité')
                            ->required()
                            ->numeric()
                            ->rules(['required', 'numeric', 'min:0'])
                            ->suffix('DA'),
                    ]),

                Forms\Components\RichEditor::make('note')
                    ->label('Note'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('farmer.fullname')
                    ->label('Agriculteurs')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('farm.code')
                    ->label('Culture')
                    ->searchable()
                    ->sortable(false),

                Tables\Columns\TextColumn::make('date_preconisation')
                    ->label('Date de préconisation')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('note')
                    ->label('Note')->html()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('farmer_id')
                    ->label('Agriculteur')
                    ->relationship('farmer', 'fullname'),

                Tables\Filters\SelectFilter::make('farm_id')
                    ->label('Culture')
                    ->relationship('farm', 'code'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                PrintPreconisationAction::create(),

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
            'index' => Pages\ListPreconisations::route('/'),
            'create' => Pages\CreatePreconisation::route('/create'),
            'edit' => Pages\EditPreconisation::route('/{record}/edit'),
        ];
    }
}
