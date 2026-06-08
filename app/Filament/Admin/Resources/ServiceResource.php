<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    //protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service Info')->schema([
                Forms\Components\Select::make('provider_id')
                    ->relationship('provider', 'company_name')
                    ->searchable()->preload()->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()->preload()->required(),
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('short_description')->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Pricing & Availability')->schema([
                Forms\Components\Select::make('price_type')
                    ->options(['fixed' => 'Fixed Price', 'hourly' => 'Per Hour', 'quote' => 'Quote Required', 'free' => 'Free'])
                    ->default('fixed')->required(),
                Forms\Components\TextInput::make('price')->numeric()->prefix('$')->step(0.01),
                Forms\Components\TextInput::make('duration_minutes')->numeric()->suffix('min'),
                Forms\Components\TagsInput::make('tags')->separator(','),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Toggle::make('is_featured'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('provider.company_name')->label('Provider')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('price_type')->badge()->color(fn ($state) => match($state) {
                    'free' => 'success', 'quote' => 'warning', default => 'info',
                }),
                Tables\Columns\TextColumn::make('price')->money('MXN')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\SelectFilter::make('category_id')->relationship('category', 'name'),
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

    public static function canAccess(): bool
    {
        $admin = auth('admin')->user();
        return $admin && ($admin->isSuperAdmin() || $admin->hasPermissionTo('manage catalog'));
    }
}
