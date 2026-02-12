<?php

namespace App\Filament\Admin\Resources\TasOrderResource\Pages;

use App\Filament\Admin\Resources\TasOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTasOrder extends ViewRecord
{
    protected static string $resource = TasOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
