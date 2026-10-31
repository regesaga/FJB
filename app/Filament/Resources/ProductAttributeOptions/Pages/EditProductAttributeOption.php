<?php

namespace App\Filament\Resources\ProductAttributeOptions\Pages;

use App\Filament\Resources\ProductAttributeOptions\ProductAttributeOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductAttributeOption extends EditRecord
{
    protected static string $resource = ProductAttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
