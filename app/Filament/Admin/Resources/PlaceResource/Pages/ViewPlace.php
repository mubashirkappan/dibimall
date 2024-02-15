<?php

namespace App\Filament\Admin\Resources\PlaceResource\Pages;

use App\Filament\Admin\Resources\PlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlace extends ViewRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
