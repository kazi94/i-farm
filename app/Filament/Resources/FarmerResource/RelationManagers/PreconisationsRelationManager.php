<?php

namespace App\Filament\Resources\FarmerResource\RelationManagers;

use App\Models\IntrantCategory;
use Filament\Forms;
use App\Models\Farm;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Intrant;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Preconisation;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Repeater;
use App\Actions\PrintPreconisationAction;
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
                    Forms\Components\Select::make('farm_id')
                        ->options(fn(RelationManager $livewire) => Farm::where('farmer_id', $livewire->getOwnerRecord()['id'])->get()->pluck('code', 'id'))
                        ->required()
                        ->label('Culture')
                        ->default(1),
                    Forms\Components\Select::make('category_id')
                        ->options(fn(RelationManager $livewire) => IntrantCategory::all()->pluck('name', 'id'))
                        ->required()
                        ->live()
                        ->label('Catégorie')
                        ->default(1),
                ])->columns(3),

                Forms\Components\Section::make([
                    Repeater::make('preconisationItems')
                        ->label('Produits')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('intrant_id')
                                ->options(Intrant::take(20)->get()->pluck('name_fr', 'id'))
                                ->getSearchResultsUsing(fn(string $search): array => Intrant::where('name_fr', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name_fr', 'id')
                                    ->toArray())
                                ->getOptionLabelUsing(fn($value): ?string => Intrant::find($value)?->name_fr)
                                ->required()
                                ->label('Intrant')
                                ->searchable()
                                ->placeholder('Choisissez un intrant')
                                ->preload(),
                            Forms\Components\TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->label('Quantite')
                                ->minValue(0)
                                ->default(1),
                            Forms\Components\Select::make('unit_id')
                                ->required()
                                ->label('Unite')
                                ->options(Unit::all()->pluck('name', 'id'))
                                ->default(1),
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->label('Prix')
                                ->default('0')
                                ->numeric()
                                ->suffix('DA')
                                ->minValue(0),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('farm.code')
                    ->label('Culture'),
                Tables\Columns\TextColumn::make('date_preconisation')
                    ->label('Date de preconisation')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->limit(30)->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modalWidth(MaxWidth::FiveExtraLarge),


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
}
