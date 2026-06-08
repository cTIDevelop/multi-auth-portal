<?php

namespace App\Filament\Provider\Resources\ProductResource\Pages;

use App\Filament\Provider\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth('provider')->id();
        return $data;
    }
}
