<?php

namespace App\Filament\Resources\FarmerResource\RelationManagers;

use Filament\Forms;
use App\Models\Farm;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Intrant;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Depredateur;
use App\Models\Preconisation;
use App\Models\IntrantCulture;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\Alignment;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Actions\Action;
use App\Actions\ArabicPrintPreconisationAction;
use App\Actions\FrenchPrintPreconisationAction;
use Filament\Resources\RelationManagers\RelationManager;

class PreconisationsRelationManager extends RelationManager
{
    protected static string $relationship = 'preconisations';

    protected static ?string $title = 'Préconisations';
    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Action::make('tests')->action('createAndClose'),
        ];
    }

    public function createAndClose(): void
    {
        // ...
    }
    public function form(Form $form): Form
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
                    Forms\Components\Select::make('culture_id')
                        ->options(fn(RelationManager $livewire) => Farm::with('culture')->where('farmer_id', $livewire->getOwnerRecord()['id'])->get()->pluck('culture.name', 'culture.id'))
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
                                    fn(Get $get, RelationManager $livewire): View => view(
                                        'filament.pages.actions.preconisation-farm-details',
                                        [
                                            'farm' => Farm::with('unit')
                                                ->where('culture_id', $get('culture_id'))
                                                ->where('farmer_id', $livewire->getOwnerRecord()['id'])
                                                ->first()
                                        ],
                                    )
                                )
                        ),
                    Forms\Components\Select::make('depredateur_id')
                        ->options(
                            fn(Get $get) => Depredateur::join('culture_intrant', 'depredateurs.id', '=', 'culture_intrant.depredateur_id')
                                ->where('culture_intrant.culture_id', '=', $get('culture_id'))
                                ->distinct()
                                ->pluck('depredateurs.name', 'depredateurs.id')
                                ->map(fn($val) => ucfirst($val))
                        )
                        ->live()
                        ->label('Déprédateur'),
                ])->columns(4),

                Forms\Components\Section::make([
                    Repeater::make('preconisationItems')
                        ->label('Produits')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('intrant_id')
                                ->options(
                                    function (Get $get) {
                                        $intrants = $get('../../culture_id') ?
                                            Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                                ->join('units', 'culture_intrant.unit_id', 'units.id')
                                                ->where('culture_intrant.culture_id', $get('../../culture_id'))
                                                ->when(
                                                    $get('../../depredateur_id'),
                                                    fn($query) =>
                                                    $query->where('culture_intrant.depredateur_id', $get('../../depredateur_id'))
                                                )
                                                ->distinct()
                                                ->get(['intrants.name_fr', 'intrants.id', 'culture_intrant.dose_min', 'culture_intrant.dose_max', \DB::raw('units.name AS unitName'), 'culture_intrant.price', 'culture_intrant.unit_id'])
                                                ->map(function ($intrant) {

                                                    $description = $intrant->name_fr . ' (' . ($intrant->dose_min == $intrant->dose_max ? $intrant->dose_min : $intrant->dose_min . '-' . $intrant->dose_max) . ' ' . $intrant->unitName . ')';

                                                    return [
                                                        'id' => $intrant->id,
                                                        'description' => $description,
                                                    ];
                                                })
                                                ->pluck('description', 'id')
                                            :
                                            Intrant::take(20)->get()->pluck('name_fr', 'id');
                                        return $intrants;
                                    }
                                )
                                ->getSearchResultsUsing(fn(string $search, Get $get): array =>
                                    Intrant::join('culture_intrant', 'intrants.id', 'culture_intrant.intrant_id')
                                        ->where('culture_id', $get('../../culture_id'))
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

                                        // $set('unit_id', $intrantCulture ? $intrantCulture->unit_id : null);

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
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->label('Prix')
                                ->default(0)
                                ->numeric()
                                ->suffix('DA')
                                ->minValue(0),
                        ])
                        ->columnSpanFull()
                        ->columns(5)

                ]),


                Forms\Components\Section::make([
                    Forms\Components\RichEditor::make('note')
                        ->label('Note'),

                ])->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('farm.culture.name')
                    ->label('Culture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_preconisation')
                    ->label('Date de preconisation')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->limit(30)->html(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('farm_id')
                    ->label('Culture')
                    ->relationship('farm.culture', 'name')
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modalWidth(MaxWidth::FiveExtraLarge)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        $data['farm_id'] = Farm::where('culture_id', $data['culture_id'])->get()->first()->id;

                        return $data;
                    }),


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
}
