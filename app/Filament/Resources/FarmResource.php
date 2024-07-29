<?php

namespace App\Filament\Resources;

use App\Models\Farm;
use App\Models\Unit;
use App\Models\Farmer;
use App\Models\Culture;
use App\Models\CultureSetting;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use App\Filament\Resources\FarmResource\Pages;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;
    protected static ?string $pluralModelLabel = 'Cultures';

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('farmer_id')
                    ->options(fn(Get $get) => Farmer::all()->pluck('fullname', 'id')->toArray())
                    ->relationship('farmer', 'fullname')
                    ->required()
                    ->searchable()
                    ->live()
                    ->preload()
                    ->label('Agriculteur'),
                TextInput::make('code')
                    ->default('FARM0000' . Farm::count() + 1)
                    ->required(),
                TextInput::make('area')
                    ->label('Superficie')
                    ->default(0)
                    ->required(),
                Select::make('unit_id')
                    ->label('Unité')
                    ->options(fn(Get $get) => Unit::whereIn('name', ['ha', 'mètre'])->get()->pluck('name', 'id'))
                    ->required(),
                TextInput::make('age')
                    ->label('Age')
                    ->default(0)
                    ->suffix('ans'),
                TextInput::make('density')
                    ->label('Densité de la plantation')
                    ->default(0)
                    ->numeric()
                    ->suffix('P/H'),
                TextInput::make('distance_tree')
                    ->label('Distance Arbre')
                    ->default(0)
                    ->numeric()
                    ->suffix('m'),
                TextInput::make('distance_line')
                    ->label('Distance Ligne')
                    ->default(0)
                    ->numeric()
                    ->suffix('m'),

                Select::make('culture_id')
                    ->label('Culture')
                    ->relationship('culture', 'name')
                    ->options(Culture::all()->pluck('name', 'id'))
                    ->live()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Culture')
                            ->required(),
                    ])->editOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Culture')
                                ->required(),
                        ]),
                Select::make('culture_setting_id')
                    ->label('Paramètres de Culture')
                    ->hidden(fn(Get $get) => !$get('culture_id'))
                    ->options(fn(Get $get) => CultureSetting::where('culture_id', $get('culture_id'))->pluck('name', 'id')->toArray())
                    ->required()
                    ->live()
                    ->relationship('cultureSetting', 'name', fn(Builder $query, Get $get) => $query->where('culture_id', $get('culture_id')))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Paramètres de Culture')
                            ->unique('culture_settings', 'name', null, true)
                            ->required()
                            ->validationMessages([
                                'unique' => 'Ce nom existe déja'
                            ]),
                        Select::make('culture_id')
                            ->label('Culture')
                            ->unique('culture_settings', 'name', null, true)
                            ->options(fn(Get $get) => Culture::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->validationMessages([
                                'unique' => 'Ce nom existe déja'
                            ])
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Paramètres de Culture')
                            ->required(),
                        Select::make('culture_id')
                            ->label('Culture')
                            ->searchable()
                            ->options(fn(Get $get) => Culture::all()->pluck('name', 'id'))
                            ->required()
                    ]),
                Select::make('culture_variante_id')
                    ->label('Variété')
                    ->hidden(fn(Get $get) => !$get('culture_setting_id'))
                    // ->options(fn(Get $get) => CultureVariante::where('culture_setting_id', $get('culture_setting_id'))->pluck('name', 'id')->toArray())
                    ->required()
                    ->relationship('cultureVariante', 'name', fn(Builder $query, Get $get) => $query->where('culture_setting_id', $get('culture_setting_id')))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Variété')
                            ->required(),
                        Select::make('culture_setting_id')
                            ->label('Paramètre de Culture')
                            ->options(fn(Get $get) => CultureSetting::all()->pluck('name', 'id'))
                            ->required()
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Variété')
                            ->required(),
                        Select::make('culture_setting_id')
                            ->label('Paramètre de Culture')
                            ->options(fn(Get $get) => CultureSetting::all()->pluck('name', 'id'))
                            ->required()
                    ]),
                Section::make('Besoins en unité fetilisantes')
                    ->schema([
                        TextInput::make('n')
                            ->placeholder('N')
                            ->numeric(),
                        TextInput::make('p')
                            ->placeholder('P')
                            ->numeric(),
                        TextInput::make('k')
                            ->placeholder('K')
                            ->numeric(),
                        TextInput::make('ca')
                            ->placeholder('CA')
                            ->numeric(),
                        TextInput::make('s')
                            ->placeholder('S')
                            ->numeric(),
                        TextInput::make('so3')
                            ->placeholder('SO3')
                            ->numeric(),
                        TextInput::make('mgo')
                            ->placeholder('MgO')
                            ->numeric(),
                        TextInput::make('b')
                            ->placeholder('B')
                            ->numeric(),
                        TextInput::make('cu')
                            ->placeholder('NCU')
                            ->numeric(),
                        TextInput::make('mn')
                            ->placeholder('Mn')
                            ->numeric(),
                        TextInput::make('fe')
                            ->placeholder('Fe')
                            ->numeric(),

                    ])
                    ->columns(6)

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
                Tables\Columns\TextColumn::make('culture.name')
                    ->sortable()
                    ->label('Culture'),
                Tables\Columns\TextColumn::make('cultureVariante.name')
                    ->sortable()
                    ->label('Famille'),
                Tables\Columns\TextColumn::make('cultureSetting.name')
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
