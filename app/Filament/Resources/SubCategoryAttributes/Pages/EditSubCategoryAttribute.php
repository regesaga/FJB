<?php

namespace App\Filament\Resources\SubCategoryAttributes\Pages;

use App\Filament\Resources\SubCategoryAttributes\SubCategoryAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubCategoryAttribute extends EditRecord
{
    protected static string $resource = SubCategoryAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
