<?php

namespace App\Filament\Resources\SubCategoryAttributes\Pages;

use App\Filament\Resources\SubCategoryAttributes\SubCategoryAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubCategoryAttributes extends ListRecords
{
    protected static string $resource = SubCategoryAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
