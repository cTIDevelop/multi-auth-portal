<?php

namespace App\Filament\Provider\Resources\ServiceResource\Pages;

use App\Filament\Provider\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();
        abort_unless(
            $this->getRecord()->provider_id === auth('provider')->id(),
            403,
            'You can only edit your own services.'
        );
    }
}
