<?php

namespace App\Filament\Resources;

use Tabs\Tab;
use Filament\Forms;
use App\Models\Farm;
use App\Models\User;
use Filament\Tables;
use App\Models\Daira;
use App\Models\Farmer;
use App\Models\Wilaya;
use App\Models\Commune;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Imports\FarmersImport;
use App\Models\CultureSetting;
use App\Imports\ProductsImport;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Group;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\ImageColumn;
use App\Forms\Components\LocalisationMap;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FarmerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FarmerResource\RelationManagers;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static ?string $modelLabel = 'Agriculteur';

    protected static ?string $pluralModelLabel = 'Agriculteurs';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static bool $shouldSkipAuthorization = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()
                    ->schema([
                        Section::make('Informations générales')
                            ->schema([

                                Forms\Components\TextInput::make('fullname')
                                    ->required()
                                    ->label('Nom complet')
                                    ->maxLength(200)
                                    ->placeholder('Mohamed Ali')
                                    ->columnSpan(2)
                                    ->unique('farmers', 'fullname', null, true)
                                    ->validationMessages([
                                        'unique' => 'Ce nom existe déja',
                                    ]),
                                Forms\Components\TextInput::make('address')
                                    ->label('Adresse')
                                    ->placeholder('Rue 1, Rue 2, ...)')
                                    ->maxLength(100)
                                    ->columnSpan(2),
                                Forms\Components\Select::make('wilaya_id')
                                    ->label('Wilaya')
                                    ->options(fn(Get $get) => Wilaya::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Forms\Components\Select::make('daira_id')
                                    ->label('Daira')
                                    ->hidden(fn(Get $get) => !$get('wilaya_id'))
                                    ->options(fn(Get $get) => Daira::where('wilaya_id', $get('wilaya_id'))->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Forms\Components\Select::make('commune_id')
                                    ->label('Commune')
                                    ->hidden(fn(Get $get) => $get('daira_id') == null)
                                    ->options(fn(Get $get) => Commune::where('daira_id', $get('daira_id'))->pluck('name', 'id'))
                                    ->required()
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->label('Email')
                                    ->placeholder('yN4oT@example.com')
                                    ->maxLength(100),
                                Forms\Components\Repeater::make('contacts')
                                    ->label('Contacts')
                                    ->relationship('contacts')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nom')
                                            ->placeholder('Nom de l\'agriculteur')
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('phone')
                                            ->label('Téléphone')
                                            ->placeholder('06 00 00 00 00')
                                            ->tel()
                                            ->maxLength(20)
                                            ->unique('contacts', 'phone', null, true)
                                            ->validationMessages([
                                                'unique' => 'Ce numéro existe déja',
                                            ]),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->placeholder('yN4oT@example.com')
                                            ->email()
                                            ->maxLength(100)
                                            ->unique('contacts', 'email', null, true)
                                            ->validationMessages([
                                                'unique' => 'Cet email existe déja',

                                            ]),

                                    ])
                                    ->columns(3)
                                    ->columnSpan(2),

                            ])
                            ->columns(2),
                        Section::make('Informations de l\'agriculture')
                            ->schema([

                                Forms\Components\Select::make('activity')
                                    ->required()
                                    ->options([
                                        'culture' => 'Culture',
                                        'culture_livestock' => 'Culture et Cheptel',
                                    ])
                                    ->label('Activité')
                                    ->default('culture')
                                    ->native(false),
                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'silver' => 'Argent',
                                        'gold' => 'Or',
                                        'bronze' => 'Bronze',
                                    ])
                                    ->label('Statut')
                                    ->default('bronze')
                                    ->native(false),
                                Forms\Components\RichEditor::make('note')
                                    ->maxLength(500)
                                    ->columnSpan(2),
                            ])
                            ->columns(2),

                    ]),
                Group::make()
                    ->schema([
                        Section::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('website')
                                    ->label('Site Web')
                                    ->hintIcon('heroicon-o-link')
                                    ->placeholder('Lien vers le site de l\'agriculteur')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('facebook_url')
                                    ->label('Facebook')
                                    ->placeholder('Lien vers le compte Facebook de l\'agriculteur')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('twitter_url')
                                    ->label('Twitter')
                                    ->placeholder('Lien vers le compte Twitter de l\'agriculteur')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('instagram_url')
                                    ->label('Instagram')
                                    ->placeholder('Lien vers le compte Instagram de l\'agriculteur')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('linkedin_url')
                                    ->label('LinkedIn')
                                    ->placeholder('Lien vers le compte LinkedIn de l\'agriculteur')
                                    ->maxLength(100),
                                Hidden::make('code')
                                    ->default('FARMER0000' . Farmer::count() + 1)
                            ]),
                        Section::make('Photo')
                            ->schema([
                                Forms\Components\FileUpload::make('image_url')
                                    ->image(),
                            ]),
                    ]),
                Section::make('Localisation')
                    ->schema([
                        LocalisationMap::make('location')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('latitude', $state['latitude']);
                                $set('longitude', $state['longitude']);
                            }),
                        TextInput::make('latitude')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                ds($state);
                                $set('latitude', $state['latitude']);
                            })
                            ->readOnly(),
                        TextInput::make('longitude')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('longitude', $state['longitude']);
                            })
                            ->readOnly(),


                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Photo')
                    ->size(40)
                    ->circular(),
                Tables\Columns\TextColumn::make('fullname')
                    ->label('Nom complet')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commune.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daira.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wilaya.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adresse')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('phone1')
                    ->label('Portable 1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone2')
                    ->label('Portable 2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('Site Web')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('facebook_url')
                    ->label('Facebook')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('twitter_url')
                    ->label('Twitter')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('instagram_url')
                    ->label('Instagram')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('linkedin_url')
                    ->label('LinkedIn')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('note')
                    ->label('Observations')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),



                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity')
                    ->label('Activité')
                ,
                Tables\Columns\TextColumn::make('status')
                    ->label('Etat'),
            ])
            ->filters([
                // ADD STATUS FILTER
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'silver' => 'Argent',
                        'gold' => 'Or',
                        'bronze' => 'Bronze',
                    ]),
                // ADD ACTIVITY FILTER
                Tables\Filters\SelectFilter::make('activity')
                    ->options([
                        'culture' => 'Culture',
                        'culture_livestock' => 'Culture et Chaptel',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('import')
                    ->label('Importer')
                    ->form([
                        Section::make('Importer')
                            ->schema([
                                FileUpload::make('import_file')
                                    ->label('Importer')
                                    ->required()
                                    ->storeFiles(false)
                            ])
                    ])
                    ->action(function (array $data) {
                        debugbar()->info($data);

                        // get file from livewire folder

                        // $file = public_path('storage/livewire-tmp/' . $data['import_file']);

                        Excel::import(new FarmersImport, $data['import_file']);

                    }),

            ]);
        ;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FarmsRelationManager::class,
            RelationManagers\PreconisationsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFarmers::route('/'),
            'create' => Pages\CreateFarmer::route('/create'),
            'edit' => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('note')
                    ->columnSpanFull(),
            ]);
    }


}
