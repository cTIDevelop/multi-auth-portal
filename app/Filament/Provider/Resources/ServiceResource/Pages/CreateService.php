<?php

namespace App\Filament\Provider\Resources\ServiceResource\Pages;

use App\Filament\Provider\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth('provider')->id();
        return $data;
    }
}
