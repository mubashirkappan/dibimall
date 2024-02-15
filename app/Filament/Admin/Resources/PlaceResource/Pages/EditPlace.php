<?php

namespace App\Filament\Admin\Resources\PlaceResource\Pages;

use App\Filament\Admin\Resources\PlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
