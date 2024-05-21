<?php

namespace App\Filament\Resources\IntrantResource\RelationManagers;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PrincipeActif;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class IntrantsPrincipesActifsRelationManager extends RelationManager
{
    protected static string $relationship = 'IntrantsPrincipesActifs';
    protected static bool $isLazy = false;
    protected static ?string $title = 'Principes actifs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('principe_actif_id')
                    ->label('Principe Actif')
                    ->options(PrincipeActif::all()->pluck('name_fr', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('principeActif', 'name_fr')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Principe Actif')
                            ->required(),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Principe Actif')
                            ->required(),
                    ]),
                Forms\Components\TextInput::make('concentration')
                    ->label('Concentration')
                    ->numeric()
                    ->rules(['required', 'numeric', 'min:0'])
                    ->validationMessages([
                        'numeric' => 'La concentration doit être un nombre',
                        'min' => 'La concentration doit être un nombre positif',

                    ]),
                Forms\Components\Select::make('unit_id')
                    ->label('Unité')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('unit', 'name')
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('principeActif.name_fr')
                    ->label('Principe Actif')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('concentration')
                    ->label('Concentration')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unité'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('principe_actif_id')
                    ->label('Principe Actif')
                    ->options(PrincipeActif::all()->pluck('name_fr', 'id'))
                    ->multiple(),

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
