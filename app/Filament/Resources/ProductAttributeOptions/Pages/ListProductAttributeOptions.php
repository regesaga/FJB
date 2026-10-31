<?php

namespace App\Filament\Resources\ProductAttributeOptions\Pages;

use App\Filament\Resources\ProductAttributeOptions\ProductAttributeOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductAttributeOptions extends ListRecords
{
    protected static string $resource = ProductAttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
