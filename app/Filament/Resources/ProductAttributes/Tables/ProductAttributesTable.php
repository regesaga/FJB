<?php

namespace App\Filament\Resources\ProductAttributes\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Atribut')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Tipe Pertanyaan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('required')
                    ->label('Wajib Diisi')
                    ->boolean(),

                IconColumn::make('filterable')
                    ->label('Filter')
                    ->boolean(),

                IconColumn::make('searchable')
                    ->label('Pencarian')
                    ->boolean(),

                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe Pertanyaan')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'textarea' => 'Textarea',
                        'date' => 'Date',
                        'select' => 'Select',
                        'checkbox' => 'Checkbox',
                        'radio' => 'Radio',
                    ]),

                TernaryFilter::make('required')
                    ->label('Wajib Diisi'),

                TernaryFilter::make('filterable')
                    ->label('Dapat Difilter'),

                TernaryFilter::make('searchable')
                    ->label('Dapat Dicari'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('sort');
    }
}