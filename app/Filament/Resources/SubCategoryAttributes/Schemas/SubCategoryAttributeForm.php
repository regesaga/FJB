<?php

namespace App\Filament\Resources\SubCategoryAttributes\Schemas;

use App\Models\ProductAttribute;
use App\Models\SubCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubCategoryAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('sub_category_id')
                ->label('Subkategori')
                ->relationship(
                    name: 'subCategory',
                    titleAttribute: 'name'
                )
                ->searchable()
                ->preload()
                ->required(),

            Select::make('product_attribute_id')
                ->label('Atribut / Pertanyaan')
                ->relationship(
                    name: 'productAttribute',
                    titleAttribute: 'name'
                )
                ->searchable()
                ->preload()
                ->required(),

            Select::make('listing_type')
                ->label('Jenis Listing')
                ->options([
                    'product' => 'Produk',
                    'service' => 'Jasa',
                    'both' => 'Keduanya',
                ])
                ->default('both')
                ->required()
                ->native(false),

            Toggle::make('required')
                ->label('Wajib Diisi')
                ->default(false),

            Toggle::make('filterable')
                ->label('Dapat Digunakan Sebagai Filter')
                ->default(false),

            Toggle::make('searchable')
                ->label('Dapat Dicari')
                ->default(true),

            Toggle::make('visible')
                ->label('Ditampilkan di Form')
                ->default(true),

            TextInput::make('sort')
                ->label('Urutan Pertanyaan')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }
}