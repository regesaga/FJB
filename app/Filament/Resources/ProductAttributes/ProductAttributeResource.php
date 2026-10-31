<?php

namespace App\Filament\Resources\ProductAttributes;

use App\Filament\Resources\ProductAttributes\Pages\CreateProductAttribute;
use App\Filament\Resources\ProductAttributes\Pages\EditProductAttribute;
use App\Filament\Resources\ProductAttributes\Pages\ListProductAttributes;
use App\Filament\Resources\ProductAttributes\Schemas\ProductAttributeForm;
use App\Filament\Resources\ProductAttributes\Tables\ProductAttributesTable;
use App\Models\ProductAttribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductAttributeResource extends Resource
{
    protected static ?string $model = ProductAttribute::class;

    protected static ?string $recordRouteKeyName = 'public_id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Atribut Produk';

    protected static ?string $modelLabel = 'Atribut Produk';

    protected static ?string $pluralModelLabel = 'Atribut Produk';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return ProductAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductAttributes::route('/'),
            'create' => CreateProductAttribute::route('/create'),
            'edit' => EditProductAttribute::route('/{record}/edit'),
        ];
    }
}