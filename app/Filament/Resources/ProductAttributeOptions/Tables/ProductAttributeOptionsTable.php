<?php

namespace App\Filament\Resources\ProductAttributeOptions\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductAttributeOptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('productAttribute.name')
                    ->label('Atribut Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Nilai Opsi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_attribute_id')
                    ->label('Atribut Produk')
                    ->relationship('productAttribute', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('sort');
    }
}