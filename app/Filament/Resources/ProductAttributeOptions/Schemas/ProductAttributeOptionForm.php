<?php

namespace App\Filament\Resources\ProductAttributeOptions\Schemas;

use App\Models\ProductAttribute;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductAttributeOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_attribute_id')
                    ->label('Atribut Produk')
                    ->relationship(
                        name: 'productAttribute',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->whereIn('type', ['select', 'checkbox', 'radio'])
                            ->orderBy('name')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('value')
                    ->label('Nilai Opsi')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sort')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}