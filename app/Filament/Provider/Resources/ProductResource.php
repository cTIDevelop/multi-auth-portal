<?php

namespace App\Filament\Provider\Resources;

use App\Filament\Provider\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    //protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'My Catalog';
    protected static ?string $navigationLabel = 'My Products';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('provider_id', auth('provider')->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Details')->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()->preload()->required(),
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                Forms\Components\TextInput::make('sku')->label('SKU')->maxLength(100),
                Forms\Components\TextInput::make('brand')->maxLength(100),
                Forms\Components\TextInput::make('short_description')->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Pricing & Inventory')->schema([
                Forms\Components\TextInput::make('price')
                    ->numeric()->prefix('$')->step(0.01)->required(),
                Forms\Components\TextInput::make('compare_price')
                    ->numeric()->prefix('$')->step(0.01)->label('Compare at Price'),
                Forms\Components\TextInput::make('stock_quantity')
                    ->numeric()->default(0)->required()->label('Stock'),
                Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
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
                Tables\Columns\TextColumn::make('sku')->copyable()->toggleable(),
                Tables\Columns\TextColumn::make('price')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('compare_price')->money('MXN')->toggleable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $provider = auth('provider')->user();
        return $provider && ($provider->hasPermissionTo('manage own products') || $provider->hasRole('provider'));
    }
}
