<?php

namespace App\Filament\Resources\ReadBookResource\Pages;

use App\Filament\Resources\ReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReadBooks extends ListRecords
{
    protected static string $resource = ReadBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
