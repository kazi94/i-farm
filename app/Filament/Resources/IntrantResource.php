<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Firm;
use Filament\Tables;
use App\Models\Intrant;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Depredateur;
use App\Models\Distributor;
use App\Models\IntrantCategory;
use Filament\Resources\Resource;
use App\Models\IntrantSousCategory;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\IntrantResource\Pages;
use App\Filament\Resources\IntrantResource\RelationManagers;

class IntrantResource extends Resource
{
    protected static ?string $model = Intrant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations générales')
                    ->schema([
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Nom')
                            ->required()
                            ->placeholder('Nom'),
                        Forms\Components\TextInput::make('formulation')
                            ->label('Formulation')
                            ->placeholder('Formulation'),
                        Forms\Components\Select::make('intrant_category_id')
                            ->label('Catégorie')
                            ->options(IntrantCategory::all()->pluck('name', 'id'))
                            ->required()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('intrant_sous_category_id')
                            ->label('Sous Catégorie')
                            ->hidden(fn(Get $get) => $get('intrant_category_id') == null)
                            // ->options(fn(Get $get) => IntrantSousCategory::where('intrant_category_id', $get('intrant_category_id'))->pluck('name', 'id'))
                            ->required()
                            ->relationship(
                                'sousCategory',
                                'name',
                                fn(Builder $query, Get $get) => $query->where('intrant_category_id', $get('intrant_category_id'))
                            )
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Sous Catégorie')
                                    ->required(),
                                Select::make('intrant_category_id')
                                    ->label('Catégorie')
                                    ->options(fn(Get $get) => IntrantCategory::all()->pluck('name', 'id'))
                                    ->required()
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Sous Catégorie')
                                    ->required(),
                                Select::make('intrant_category_id')
                                    ->label('Catégorie')
                                    ->options(fn(Get $get) => IntrantCategory::all()->pluck('name', 'id'))
                                    ->required()
                            ]),
                        Forms\Components\TextInput::make('homologation_number')
                            ->label('N° de homologation')
                            ->placeholder('N° de homologation'),
                        Forms\Components\TextInput::make('score')
                            ->label('Score')
                            ->numeric()
                            ->placeholder('Score')
                            ->validationMessages([
                                'numeric' => 'Score doit être un nombre',
                            ]),
                        Forms\Components\Select::make('firm_id')
                            ->label('Firme')
                            ->searchable()
                            ->preload()
                            ->options(Firm::all()->pluck('name', 'id'))
                            ->relationship('firm', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Firme')
                                    ->required(),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Firme')
                                    ->required(),
                            ]),
                        Forms\Components\Select::make('distributor_id')
                            ->label('Représentant')
                            ->searchable()
                            ->preload()
                            ->options(Distributor::all()->pluck('name', 'id'))
                            ->relationship('distributor', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Représentant')
                                    ->required(),
                            ])
                            ->editOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Représentant')
                                    ->required(),
                            ]),
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Homologué')
                            ->default(false),
                    ])
                    ->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_fr')
                    ->label('Intrant')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formulation')
                    ->label('Formulation')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('intrantsPrincipesActifs')
                    ->view('tables.columns.intrant-principes-actifs')
                    ->label('Principes Actifs'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('sousCategory.name')
                    ->label('Sous Catégorie')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('homologation_number')
                    ->label('N° de homologation')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('firm.name')
                    ->label('Firme')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('distributor.name')
                    ->label('Représentant')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),


            ])
            ->filters([

                Tables\Filters\SelectFilter::make('firm_id')
                    ->label('Firme')
                    ->options(Firm::all()->pluck('name', 'id'))
                    ->multiple(),
                Filter::make('depredateur_id')
                    ->form([
                        Select::make('depredateur_id')
                            ->label('Dépredateur')
                            ->options(Depredateur::all()->pluck('name', 'id'))
                            ->multiple()
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['depredateur_id'],
                            fn(Builder $query): Builder =>
                            $query->whereRelation('intrantsCultures', 'depredateur_id', $data['depredateur_id'])

                        );
                    }),

                Tables\Filters\SelectFilter::make('intrant_category_id')
                    ->label('Catégorie')
                    ->options(IntrantCategory::all()->pluck('name', 'id'))
                    ->multiple(),
                Tables\Filters\SelectFilter::make('intrant_sous_category_id')
                    ->label('Sous Catégorie')
                    ->options(fn(Get $get) => IntrantSousCategory::all()->pluck('name', 'id'))
                    ->multiple(),

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
            RelationManagers\IntrantsPrincipesActifsRelationManager::class,
            RelationManagers\IntrantsCulturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntrants::route('/'),
            'create' => Pages\CreateIntrant::route('/create'),
            'edit' => Pages\EditIntrant::route('/{record}/edit'),
        ];
    }


}
