<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\SubCategory;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ListingManager extends Page
{
    use WithPagination;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Manajemen Listing';

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

    #[Url]
    public string $status = '';

    protected function baseQuery(): Builder
    {
        return Product::query()
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
            );
    }

    public function getStatistics(): array
    {
        $query = $this->baseQuery();

        return [
            'total' => (clone $query)->count(),
            'draft' => (clone $query)->where('status', 'draft')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'published' => (clone $query)->where('status', 'published')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
    }

    public function getDynamicAttributes()
    {
        if (! $this->subCategoryId) {
            return collect();
        }

        return ProductAttribute::query()
            ->select('product_attributes.*')
            ->join(
                'sub_category_attributes as sca',
                'sca.product_attribute_id',
                '=',
                'product_attributes.id'
            )
            ->where('sca.sub_category_id', $this->subCategoryId)
            ->where('sca.visible', true)
            ->orderBy('sca.sort')
            ->get();
    }

    public function getListings()
    {
        return $this->baseQuery()
            ->with([
                'category',
                'subCategory',
                'user',
                'attributeValues',
            ])
            ->when(
                $this->status,
                fn (Builder $query) => $query->where(
                    'status',
                    $this->status
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
            ->latest()
            ->paginate(15);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function getPageHeading(): string
    {
        if ($this->subCategoryId) {
            return SubCategory::find($this->subCategoryId)?->name
                ?? 'Manajemen Listing';
        }

        if ($this->categoryId) {
            return Category::find($this->categoryId)?->name
                ?? 'Manajemen Listing';
        }

        return 'Manajemen Listing';
    }
}