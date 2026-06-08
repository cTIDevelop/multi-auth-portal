<?php

namespace App\Filament\Provider\Resources;

use App\Filament\Provider\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    //protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'My Catalog';
    protected static ?string $navigationLabel = 'My Services';
    protected static ?int $navigationSort = 1;

    // Scope to authenticated provider's services only
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('provider_id', auth('provider')->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service Details')->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()->preload()->required()->label('Category'),
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                Forms\Components\TextInput::make('short_description')->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Pricing')->schema([
                Forms\Components\Select::make('price_type')
                    ->options(['fixed' => 'Fixed Price', 'hourly' => 'Per Hour', 'quote' => 'Quote Required', 'free' => 'Free'])
                    ->default('fixed')->required()->reactive(),
                Forms\Components\TextInput::make('price')
                    ->numeric()->prefix('$')->step(0.01)
                    ->visible(fn (Forms\Get $get) => in_array($get('price_type'), ['fixed', 'hourly'])),
                Forms\Components\TextInput::make('duration_minutes')->numeric()->suffix('minutes'),
                Forms\Components\TagsInput::make('tags')->separator(','),
                Forms\Components\Toggle::make('is_active')->default(true)->label('Published'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->badge()->color('info'),
                Tables\Columns\TextColumn::make('price_type')->badge()->color(fn ($state) => match($state) {
                    'free' => 'success', 'quote' => 'warning', default => 'info',
                }),
                Tables\Columns\TextColumn::make('price')->money('MXN'),
                Tables\Columns\IconColumn::make('is_active')->label('Published')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->since()->label('Updated'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Published'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth('provider')->id();
        return $data;
    }

    public static function canAccess(): bool
    {
        $provider = auth('provider')->user();
        return $provider && ($provider->hasPermissionTo('manage own services') || $provider->hasRole('provider'));
    }
}
