<?php

namespace App\Filament\Resources\SubCategoryAttributes\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SubCategoryAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subCategory.name')
                    ->label('Subkategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('productAttribute.name')
                    ->label('Atribut / Pertanyaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('listing_type')
                    ->label('Jenis Listing')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'product' => 'Produk',
                        'service' => 'Jasa',
                        'both' => 'Keduanya',
                        default => $state,
                    })
                    ->sortable(),

                IconColumn::make('required')
                    ->label('Wajib')
                    ->boolean(),

                IconColumn::make('filterable')
                    ->label('Filter')
                    ->boolean(),

                IconColumn::make('searchable')
                    ->label('Pencarian')
                    ->boolean(),

                IconColumn::make('visible')
                    ->label('Tampil')
                    ->boolean(),

                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('listing_type')
                    ->label('Jenis Listing')
                    ->options([
                        'product' => 'Produk',
                        'service' => 'Jasa',
                        'both' => 'Keduanya',
                    ]),

                SelectFilter::make('sub_category_id')
                    ->label('Subkategori')
                    ->relationship('subCategory', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('visible')
                    ->label('Status Tampilan'),

                TernaryFilter::make('required')
                    ->label('Wajib Diisi'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('sort');
    }
}