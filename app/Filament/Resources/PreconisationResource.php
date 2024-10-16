<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Farm;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Farmer;
use App\Models\Intrant;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Depredateur;
use App\Models\Preconisation;
use App\Models\IntrantCulture;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Actions\Action;
use App\Actions\ArabicPrintPreconisationAction;
use App\Actions\FrenchPrintPreconisationAction;
use App\Filament\Resources\PreconisationResource\Pages;

class PreconisationResource extends Resource
{
    protected static ?string $model = Preconisation::class;

    protected static ?string $modelLabel = 'Préconisation';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->label('Code')
                        ->default('PRC00' . Preconisation::max('id') + 1)
                        ->readOnly(),
                    Forms\Components\DatePicker::make('date_preconisation')
                        ->required()
                        ->label('Date de preconisation')
                        ->minDate(now()->subDays(1))
                        ->default(now())
                        ->displayFormat('d/m/Y'),
                    Forms\Components\Select::make('farmer_id')
                        ->label('Agriculteur')
                        ->options(Farmer::all()->pluck('fullname', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->relationship('farmer', 'fullname'),
                    Forms\Components\Select::make('culture_id')
                        ->options(
                            function (Get $get) {
                                $farms = Farm::with('culture')
                                    ->where('farmer_id', $get('farmer_id'))
                                    ->get();
                                return $farms->map(function ($farm) {
                                    return [
                                        'label' => $farm->name . ' - ' . $farm->culture->name,
                                        'value' => $farm->culture_id,
                                    ];
                                })->pluck('label', 'value');
                            }
                        )
                        ->required()
                        ->live()
                        ->label('Culture')
                        ->suffixAction(
                            Action::make('Détails')
                                ->modalSubmitAction(false)
                                ->color('primary')
                                ->icon('heroicon-o-information-circle')
                                ->modalAlignment(Alignment::Center)
                                ->modalWidth(MaxWidth::Small)
                                ->modalContent(
                                    fn(Get $get): View => view(
                                        'filament.pages.actions.preconisation-farm-details',
                                        [
                                            'farm' => Farm::with('unit')
                                                ->where('culture_id', $get('culture_id'))
                                                ->where('farmer_id', $get('farmer_id'))
                                                ->first()
                                        ],
                                    )
                                )
                        ),
                    Forms\Components\Select::make('depredateur_id')
                        ->options(
                            fn(Get $get) => Depredateur::join('culture_intrant', 'depredateurs.id', '=', 'culture_intrant.depredateur_id')
                                ->when(
                                    $get('culture_id'),
                                    fn($query) => $query->where('culture_intrant.culture_id', $get('culture_id'))
                                )
                                ->distinct()
                                ->pluck('depredateurs.name', 'depredateurs.id')
                                ->map(fn($val) => ucfirst($val))
                        )
                        ->live()
                        ->label('Déprédateur')
                        ->searchable(),
                ])->columns(4),

                Forms\Components\Section::make([
                    Repeater::make('preconisationItems')
                        ->label('Produits')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('intrant_id')
                                ->options(
                                    function (Get $get) {
                                        $intrants = [];
                                        if (!$get('../../culture_id') && $get('../../depredateur_id')) {

                                            $intrants = Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                                ->join('units', 'culture_intrant.unit_id', 'units.id')
                                                ->where('culture_intrant.depredateur_id', $get('../../depredateur_id'))
                                                ->distinct()
                                                ->get(['intrants.name_fr', 'intrants.id', 'culture_intrant.dose_min', 'culture_intrant.dose_max', \DB::raw('units.name AS unitName'), 'culture_intrant.price', 'culture_intrant.unit_id']);

                                        }

                                        if ($get('../../culture_id') && !$get('../../depredateur_id')) {
                                            $intrants = Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                                ->join('units', 'culture_intrant.unit_id', 'units.id')
                                                ->where('culture_intrant.culture_id', $get('../../culture_id'))
                                                ->distinct()
                                                ->get(['intrants.name_fr', 'intrants.id', 'culture_intrant.dose_min', 'culture_intrant.dose_max', \DB::raw('units.name AS unitName'), 'culture_intrant.price', 'culture_intrant.unit_id']);
                                        }

                                        if ($get('../../culture_id') && $get('../../depredateur_id')) {
                                            $intrants = Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                                ->join('units', 'culture_intrant.unit_id', 'units.id')
                                                ->where('culture_intrant.culture_id', $get('../../culture_id'))
                                                ->when(
                                                    $get('../../depredateur_id'),
                                                    fn($query) =>
                                                    $query->where('culture_intrant.depredateur_id', $get('../../depredateur_id'))
                                                )
                                                ->distinct()
                                                ->get(['intrants.name_fr', 'intrants.id', 'culture_intrant.dose_min', 'culture_intrant.dose_max', \DB::raw('units.name AS unitName'), 'culture_intrant.price', 'culture_intrant.unit_id']);
                                        }

                                        // check if intrants is empty
                                        if (empty($intrants)) {
                                            // retrieve all intrants 20
                                            $intrants = Intrant::take(20)->get()->pluck('name_fr', 'id');
                                        } else {
                                            $intrants = collect($intrants)->map(function ($intrant) {

                                                $description = $intrant->name_fr . ' (' . ($intrant->dose_min == $intrant->dose_max ? $intrant->dose_min : $intrant->dose_min . '-' . $intrant->dose_max) . ' ' . $intrant->unitName . ')';

                                                return [
                                                    'id' => $intrant->id,
                                                    'description' => $description,
                                                ];
                                            })
                                                ->pluck('description', 'id');
                                        }

                                        return $intrants;
                                    }
                                )
                                ->getSearchResultsUsing(fn(string $search, Get $get): array =>
                                    Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                        ->where('name_fr', 'like', "%{$search}%")
                                        ->limit(50)
                                        ->pluck('intrants.name_fr', 'intrants.id')
                                        ->toArray())
                                ->getOptionLabelUsing(fn($value): ?string => Intrant::find($value)?->name_fr)
                                ->required()
                                ->label('Intrant')
                                ->searchable()
                                ->placeholder('Choisissez un intrant')
                                ->preload()
                                ->live()
                                ->afterStateUpdated(
                                    function (?string $state, Set $set, Get $get) {
                                        $intrantCulture = IntrantCulture::with('unit')
                                            ->where('intrant_id', $state)
                                            ->where('culture_id', $get('../../culture_id'))
                                            ->where('depredateur_id', $get('../../depredateur_id'))
                                            ->first();

                                        $set(
                                            'price',
                                            $intrantCulture ? $intrantCulture->price : 0
                                        );

                                        $set('dose', $intrantCulture ? ($intrantCulture->dose_min == $intrantCulture->dose_max ? $intrantCulture->dose_min : $intrantCulture->dose_min . '-' . $intrantCulture->dose_max) . ' ' . $intrantCulture->unit->name : 0);
                                        $set('dose_ar', $intrantCulture ? ($intrantCulture->dose_min == $intrantCulture->dose_max ? $intrantCulture->dose_min : $intrantCulture->dose_min . '-' . $intrantCulture->dose_max) . ' ' . $intrantCulture->unit->name_ar : 0);

                                    }

                                ),
                            Forms\Components\TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->label('Quantite')
                                ->minValue(0)
                                ->default(1),
                            Forms\Components\Select::make('unit_id')
                                ->label('Unite')
                                ->options(Unit::all()->pluck('name', 'id'))
                                ->default(1)
                                ->searchable(),
                            Forms\Components\TextInput::make('dose')
                                ->required()
                                ->label('Dose'),
                            Hidden::make('dose_ar'),
                            Forms\Components\Select::make('usage_mode')
                                ->required()
                                ->label('Mode d\'application')
                                ->options([
                                    'foliaire_application' => 'Application foliaire',
                                    'root_application' => 'Application raçinaire',
                                ])
                                ->default('foliaire_application'),
                            // Forms\Components\TextInput::make('price')
                            //     ->required()
                            //     ->label('Prix')
                            //     ->default(0)
                            //     ->numeric()
                            //     ->suffix('DA')
                            //     ->minValue(0),
                        ])
                        ->columnSpanFull()
                        ->columns(4)

                ]),


                Forms\Components\Section::make([
                    Forms\Components\RichEditor::make('note')
                        ->label('Note'),

                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('farmer.fullname')
                    ->label('Agriculteur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('farm.culture.name')
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
            ->defaultSort('date_preconisation', 'desc')
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
                FrenchPrintPreconisationAction::create(),
                ArabicPrintPreconisationAction::create(),

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
