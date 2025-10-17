<?php

namespace App\Filament\Resources\AlertContactResource\Pages;

use App\Filament\Resources\AlertContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertContacts extends ListRecords
{
    protected static string $resource = AlertContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
