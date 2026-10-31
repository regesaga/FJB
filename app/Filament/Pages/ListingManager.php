<?php

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListingManager extends Page
{
    protected static ?string $navigationLabel = 'Semua Listing';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Manajemen Listing';

    protected static string|\BackedEnum|null $navigationIcon =
        \Filament\Support\Icons\Heroicon::OutlinedRectangleStack;

    protected string $view = 'filament.pages.listing-manager';
    #[Url]
    public ?string $listingType = null;

    #[Url]
    public ?int $categoryId = null;

    #[Url]
    public ?int $subCategoryId = null;

    #[Url]
    public string $search = '';

    public function getListings(): Builder
    {
        return Product::query()
            ->with(['category', 'subCategory', 'user'])
            ->when(
                $this->listingType,
                fn (Builder $query) => $query->where(
                    'listing_type',
                    $this->listingType
                )
            )
            ->when(
                $this->categoryId,
                fn (Builder $query) => $query->where(
                    'category_id',
                    $this->categoryId
                )
            )
            ->when(
                $this->subCategoryId,
                fn (Builder $query) => $query->where(
                    'sub_category_id',
                    $this->subCategoryId
                )
            )
            ->when(
                $this->search,
                fn (Builder $query) => $query->where(
                    'title',
                    'like',
                    '%' . $this->search . '%'
                )
            )
            ->latest();
    }
}