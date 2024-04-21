<?php

namespace App\Filament\Resources\FarmerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreconisationsRelationManager extends RelationManager
{
    protected static string $relationship = 'preconisations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_preconisation')
                    ->required()
                    ->label('Date de preconisation')
                    ->minDate(now())
                    ->default(now())
                    ->displayFormat('d/m/Y'),
                Select::make('farm_id')
                    ->relationship('farm', 'name')
                    ->required()
                    ->searchable()
                    ->label('Culture'),
                Repeater::make('preconisationItems')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->label('Produit')
                            ->searchable()
                            ->placeholder('Choisissez un produit')
                            ->preload(true),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->label('Quantite')
                            ->minValue(0)
                            ->default(1),
                        Forms\Components\Select::make('unit')
                            ->required()
                            ->label('Unite')
                            ->options([
                                'kg' => 'Kg',
                                'litre' => 'Litre',
                                'unit' => 'Unite',
                            ]),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->label('Prix')
                            ->default('0')
                            ->numeric()
                            ->suffix('DA')
                            ->minValue(0),
                    ])


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description'),
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
