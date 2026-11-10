<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
protected static bool $shouldRegisterNavigation = false;
    protected static ?string $recordRouteKeyName = 'public_id';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Semua Listing';

    protected static ?string $modelLabel = 'Listing';

    protected static ?string $pluralModelLabel = 'Listing';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }
public static function getIndexUrl(
    array $parameters = [],
    bool $isAbsolute = true,
    ?string $panel = null,
    ?\Illuminate\Database\Eloquent\Model $tenant = null,
    bool $shouldGuessMissingParameters = false,
): string {
    return route(
        'filament.admin.pages.listing-manager',
        [],
        $isAbsolute
    );
}
    public static function getPages(): array
{
    return [
        'create' => CreateProduct::route('/create'),
        'edit' => EditProduct::route('/{record}/edit'),
    ];
}
}
