<?php

namespace App\Filament\Admin\Resources\TasOrderResource\Pages;

use App\Filament\Admin\Resources\TasOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTasOrder extends EditRecord
{
    protected static string $resource = TasOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
