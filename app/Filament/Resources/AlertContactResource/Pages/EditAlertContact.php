<?php

namespace App\Filament\Resources\AlertContactResource\Pages;

use App\Filament\Resources\AlertContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlertContact extends EditRecord
{
    protected static string $resource = AlertContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
