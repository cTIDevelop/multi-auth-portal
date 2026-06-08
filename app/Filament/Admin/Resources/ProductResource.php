<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    //protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Info')->schema([
                Forms\Components\Select::make('provider_id')->relationship('provider', 'company_name')->searchable()->preload()->required(),
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('name')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('sku')->label('SKU')->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('brand')->maxLength(100),
                Forms\Components\TextInput::make('short_description')->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Pricing & Stock')->schema([
                Forms\Components\TextInput::make('price')->numeric()->prefix('$')->step(0.01)->required(),
                Forms\Components\TextInput::make('compare_price')->numeric()->prefix('$')->step(0.01)->label('Compare Price'),
                Forms\Components\TextInput::make('stock_quantity')->numeric()->default(0)->required(),
                Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
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
                Tables\Columns\TextColumn::make('provider.company_name')->label('Provider'),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('sku')->copyable()->toggleable(),
                Tables\Columns\TextColumn::make('price')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')->label('Stock')->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_featured'),
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
        $admin = auth('admin')->user();
        return $admin && ($admin->isSuperAdmin() || $admin->hasPermissionTo('manage catalog'));
    }
}
