<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProviderResource\Pages;
use App\Models\Provider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;
    //protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Provider Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'company_name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Account')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])->columns(2),
                Forms\Components\Tabs\Tab::make('Company')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('contact_person')->maxLength(255),
                        Forms\Components\TextInput::make('phone')->tel()->maxLength(20),
                        Forms\Components\TextInput::make('website')->url()->maxLength(255),
                        Forms\Components\TextInput::make('tax_id')->label('Tax ID')->maxLength(50),
                        Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Tabs\Tab::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')->maxLength(255)->columnSpanFull(),
                        Forms\Components\TextInput::make('city')->maxLength(100),
                        Forms\Components\TextInput::make('state')->maxLength(100),
                        Forms\Components\TextInput::make('country')->maxLength(100)->default('México'),
                        Forms\Components\TextInput::make('postal_code')->maxLength(20),
                    ])->columns(2),
                Forms\Components\Tabs\Tab::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
                        Forms\Components\Toggle::make('is_verified')->label('Verified'),
                        Forms\Components\DateTimePicker::make('verified_at')->label('Verified At'),
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Provider Roles'),
                    ])->columns(2),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('name')->label('Contact')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('city')->searchable(),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\IconColumn::make('is_verified')->label('Verified')->boolean(),
                Tables\Columns\TextColumn::make('services_count')->label('Services')->counts('services')->badge()->color('info'),
                Tables\Columns\TextColumn::make('products_count')->label('Products')->counts('products')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TernaryFilter::make('is_verified')->label('Verified'),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Provider $record) => ! $record->is_verified)
                    ->action(function (Provider $record) {
                        $record->update(['is_verified' => true, 'verified_at' => now()]);
                    })
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $admin = auth('admin')->user();
        return $admin && ($admin->isSuperAdmin() || $admin->hasPermissionTo('manage providers'));
    }
}
