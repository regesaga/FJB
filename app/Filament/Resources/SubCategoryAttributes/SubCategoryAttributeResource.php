<?php

namespace App\Filament\Resources\SubCategoryAttributes;

use App\Filament\Resources\SubCategoryAttributes\Pages\CreateSubCategoryAttribute;
use App\Filament\Resources\SubCategoryAttributes\Pages\EditSubCategoryAttribute;
use App\Filament\Resources\SubCategoryAttributes\Pages\ListSubCategoryAttributes;
use App\Filament\Resources\SubCategoryAttributes\Schemas\SubCategoryAttributeForm;
use App\Filament\Resources\SubCategoryAttributes\Tables\SubCategoryAttributesTable;
use App\Models\SubCategoryAttribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubCategoryAttributeResource extends Resource
{
    protected static ?string $model = SubCategoryAttribute::class;

    protected static ?string $recordRouteKeyName = 'public_id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Mapping Atribut';

    protected static ?string $modelLabel = 'Mapping Atribut';

    protected static ?string $pluralModelLabel = 'Mapping Atribut';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return SubCategoryAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubCategoryAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubCategoryAttributes::route('/'),
            'create' => CreateSubCategoryAttribute::route('/create'),
            'edit' => EditSubCategoryAttribute::route('/{record}/edit'),
        ];
    }
}