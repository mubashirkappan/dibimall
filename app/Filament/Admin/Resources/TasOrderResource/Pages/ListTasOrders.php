<?php

namespace App\Filament\Admin\Resources\TasOrderResource\Pages;

use App\Filament\Admin\Resources\TasOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasOrders extends ListRecords
{
    protected static string $resource = TasOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
