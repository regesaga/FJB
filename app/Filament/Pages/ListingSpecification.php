<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\SubCategoryAttribute;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class ListingSpecification extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'listing-specification';

    protected static ?string $title = 'Spesifikasi Listing';

    protected string $view = 'filament.pages.listing-specification';

    #[Url]
    public ?string $listing = null;

    public function getListing(): ?Product
    {
        if (! $this->listing) {
            return null;
        }

        return Product::query()
            ->with([
                'category',
                'subCategory',
                'user',
                'attributeValues',
            ])
            ->where('public_id', $this->listing)
            ->first();
    }

    public function getSpecifications()
    {
        $listing = $this->getListing();

        if (! $listing || ! $listing->sub_category_id) {
            return collect();
        }

        return SubCategoryAttribute::query()
            ->with([
                'productAttribute.options',
            ])
            ->where('sub_category_id', $listing->sub_category_id)
            ->where('listing_type', $listing->listing_type)
            ->where('visible', true)
            ->orderBy('sort')
            ->get()
            ->map(function ($mapping) use ($listing) {
                $attribute = $mapping->productAttribute;

                if (! $attribute) {
                    return null;
                }

                $answer = $listing->attributeValues
                    ->firstWhere(
                        'product_attribute_id',
                        $attribute->id
                    );

                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'required' => $mapping->required,
                    'value' => $answer?->value,
                    'options' => $attribute->options,
                ];
            })
            ->filter()
            ->values();
    }
}