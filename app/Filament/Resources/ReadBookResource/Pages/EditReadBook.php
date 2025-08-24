<?php

namespace App\Filament\Resources\ReadBookResource\Pages;

use App\Filament\Resources\ReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReadBook extends EditRecord
{
    protected static string $resource = ReadBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
