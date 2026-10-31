<?php

namespace App\Filament\Resources\ProductAttributes\Pages;

use App\Filament\Resources\ProductAttributes\ProductAttributeResource;
use App\Models\ProductAttribute;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProductAttributes extends ListRecords
{
    protected static string $resource = ProductAttributeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Atribut')
                ->badge(ProductAttribute::count()),

            'product' => Tab::make('Bank Pertanyaan Produk')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('is_recommended', true)
                        ->where('slug', 'not like', 'bank-jasa-%')
                )
                ->badge(
                    ProductAttribute::where('is_recommended', true)
                        ->where('slug', 'not like', 'bank-jasa-%')
                        ->count()
                ),

            'service' => Tab::make('Bank Pertanyaan Jasa')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('is_recommended', true)
                        ->where('slug', 'like', 'bank-jasa-%')
                )
                ->badge(
                    ProductAttribute::where('is_recommended', true)
                        ->where('slug', 'like', 'bank-jasa-%')
                        ->count()
                ),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}