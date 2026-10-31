<?php

namespace App\Filament\Resources\ProductAttributeOptions;

use App\Filament\Resources\ProductAttributeOptions\Pages\CreateProductAttributeOption;
use App\Filament\Resources\ProductAttributeOptions\Pages\EditProductAttributeOption;
use App\Filament\Resources\ProductAttributeOptions\Pages\ListProductAttributeOptions;
use App\Filament\Resources\ProductAttributeOptions\Schemas\ProductAttributeOptionForm;
use App\Filament\Resources\ProductAttributeOptions\Tables\ProductAttributeOptionsTable;
use App\Models\ProductAttributeOption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductAttributeOptionResource extends Resource
{
    protected static ?string $model = ProductAttributeOption::class;

    protected static ?string $recordRouteKeyName = 'public_id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'value';

    protected static ?string $navigationLabel = 'Opsi Atribut';

    protected static ?string $modelLabel = 'Opsi Atribut';

    protected static ?string $pluralModelLabel = 'Opsi Atribut';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return ProductAttributeOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductAttributeOptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductAttributeOptions::route('/'),
            'create' => CreateProductAttributeOption::route('/create'),
            'edit' => EditProductAttributeOption::route('/{record}/edit'),
        ];
    }
}