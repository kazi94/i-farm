<?php

namespace App\Filament\Resources\IntrantResource\RelationManagers;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Culture;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Depredateur;
use App\Models\CultureSetting;
use App\Models\CultureVariante;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class IntrantsCulturesRelationManager extends RelationManager
{
    protected static string $relationship = 'IntrantsCultures';
    protected static bool $isLazy = false;
    protected static ?string $title = 'Traitements';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('culture_id')
                    ->label('Culture')
                    ->options(Culture::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('culture', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Culture')
                            ->required(),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Culture')
                            ->required(),

                    ]),
                Select::make('culture_setting_id')
                    ->label('Paramètres de Culture')
                    ->hidden(fn(Get $get) => !$get('culture_id'))
                    ->options(fn(Get $get) => CultureSetting::where('culture_id', $get('culture_id'))->pluck('name', 'id')->toArray())
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
                            ->options(fn(Get $get) => Culture::all()->pluck('name', 'id'))
                            ->required()
                    ]),
                Select::make('culture_variante_id')
                    ->label('Variété')
                    ->hidden(fn(Get $get) => !$get('culture_setting_id'))
                    // ->options(fn(Get $get) => CultureVariante::where('culture_setting_id', $get('culture_setting_id'))->pluck('name', 'id')->toArray())
                    ->relationship('cultureVariante', 'name', fn(Builder $query, Get $get) => $query->where('culture_setting_id', $get('culture_setting_id')))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Variété')
                            ->required(),
                        Select::make('culture_setting_id')
                            ->label('Famille')
                            ->options(fn(Get $get) => CultureSetting::all()->pluck('name', 'id'))
                            ->required()
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Variété')
                            ->required(),
                        Select::make('culture_setting_id')
                            ->label('Famille')
                            ->options(fn(Get $get) => CultureSetting::all()->pluck('name', 'id'))
                            ->required()
                    ]),
                Forms\Components\Select::make('depredateur_id')
                    ->label('Depredateur')
                    ->options(Depredateur::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('depredateur', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Depredateur')
                            ->required(),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Depredateur')
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('dose_min')
                    ->label('Dose Min')
                    ->numeric()
                    ->rules(['min:0'])
                    ->validationMessages([
                        'numeric' => 'Dose Min doit etre un entier',
                        'min' => 'Dose Min doit etre superieur ou egale a 0',
                    ]),
                Forms\Components\TextInput::make('dose_max')
                    ->label('Dose Max')
                    ->numeric()
                    ->gte('dose_min')
                    ->rules(['min:0'])
                    ->validationMessages([
                        'numeric' => 'Dose Max doit etre un entier',
                        'min' => 'Dose Max doit etre superieur ou egale a 0',
                        'gte' => 'Dose Max doit etre superieur ou egale a Dose Min',
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
                Forms\Components\TextInput::make('dar_min')
                    ->label('DAR Min (j)')
                    ->numeric()
                    ->rules(['min:0'])
                    ->validationMessages([
                        'numeric' => 'DAR Min doit etre un entier',
                        'min' => 'DAR Min doit etre superieur ou egale a 0',
                    ]),
                Forms\Components\TextInput::make('dar_max')
                    ->label('DAR Max (j)')
                    ->numeric()
                    ->gte('dar_min')
                    ->rules(['min:0'])
                    ->validationMessages([
                        'numeric' => 'DAR Max doit etre un entier',
                        'min' => 'DAR Max doit etre superieur ou egale a 0',
                        'gte' => 'DAR Max doit etre superieur ou egale a DAR Min',
                    ]),
                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->numeric()
                    ->rules(['min:0'])
                    ->suffix('DA')
                    ->validationMessages([
                        'numeric' => 'DAR Max doit etre un entier',
                    ]),
                Forms\Components\Textarea::make('observation')
                    ->label('Observation')
                    ->columnSpan(3)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('culture.name')
                    ->label('Culture')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('depredateur.name')
                    ->label('Depredateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_min')
                    ->label('Dose Min')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_max')
                    ->label('Dose Max')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unité')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dar_min')
                    ->label('DAR Min ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dar_max')
                    ->label('DAR Max (j)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('observation')
                    ->label('Observation')
                    ->columnSpan(3)->html(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('culture_id')
                    ->label('Culture')
                    ->options(Culture::all()->pluck('name', 'id'))
                    ->multiple()

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
