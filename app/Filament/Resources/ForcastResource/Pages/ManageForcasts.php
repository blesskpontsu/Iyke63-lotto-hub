<?php

namespace App\Filament\Resources\ForcastResource\Pages;

use App\Filament\Resources\ForcastResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageForcasts extends ManageRecords
{
    protected static string $resource = ForcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
