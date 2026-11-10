<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
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

    // FILTER
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

    #[Url]
    public string $publication = '';

    // PAGINATION
    public string $perPage = '50';

    // SORTING
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    // MODAL INFORMASI
    public ?string $selectedListingPublicId = null;

    public ?string $activeInfo = null;

    /*
    |--------------------------------------------------------------------------
    | QUERY DASAR
    |--------------------------------------------------------------------------
    */

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
            )
            ->when(
                $this->publication === 'published',
                fn (Builder $query) => $query->where(
                    'status',
                    'published'
                )
            )
            ->when(
                $this->publication === 'unpublished',
                fn (Builder $query) => $query->where(
                    'status',
                    '!=',
                    'published'
                )
            )
            ->when(
                $this->status !== '',
                fn (Builder $query) => $query->where(
                    'status',
                    $this->status
                )
            )
            ->when(
                $this->search !== '',
                function (Builder $query) {
                    $search = '%' . $this->search . '%';

                    $query->where(function (Builder $q) use ($search) {
                        $q->where('title', 'like', $search)
                            ->orWhereHas('user', function (Builder $user) use ($search) {
                                $user->where('name', 'like', $search);
                            });
                    });
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    public function getStatistics(): array
    {
        $query = $this->baseQuery();

        return [
            'total' => (clone $query)->count(),

            'draft' => (clone $query)
                ->where('status', 'draft')
                ->count(),

            'pending' => (clone $query)
                ->where('status', 'pending')
                ->count(),

            'published' => (clone $query)
                ->where('status', 'published')
                ->count(),

            'rejected' => (clone $query)
                ->where('status', 'rejected')
                ->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KATEGORI
    |--------------------------------------------------------------------------
    */

    public function getCategories()
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    public function getSubCategories()
{
    return SubCategory::query()
        ->when(
            $this->categoryId,
            fn (Builder $query) => $query->where(
                'category_id',
                $this->categoryId
            )
        )
        ->orderBy('name')
        ->get();
}

    public function updatedCategoryId(): void
    {
        $this->subCategoryId = null;

        $this->resetPage();
    }

    public function updatedSubCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedListingType(): void
    {
        $this->categoryId = null;
        $this->subCategoryId = null;

        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPublication(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    public function updatedPerPage(): void
    {
        if (! in_array($this->perPage, ['50', '100', 'all'], true)) {
            $this->perPage = '50';
        }

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | SORTING
    |--------------------------------------------------------------------------
    */

    public function sortBy(string $field): void
    {
        $allowedFields = [
            'title',
            'category',
            'subcategory',
            'price',
            'seller',
            'status',
            'created_at',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection =
                $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    protected function applySorting(Builder $query): Builder
    {
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        switch ($this->sortField) {
            case 'title':
            case 'price':
            case 'status':
            case 'created_at':
                $query->orderBy($this->sortField, $direction);
                break;

            case 'category':
                $query->orderBy(
                    Category::select('name')
                        ->whereColumn('categories.id', 'products.category_id'),
                    $direction
                );
                break;

            case 'subcategory':
                $query->orderBy(
                    SubCategory::select('name')
                        ->whereColumn('sub_categories.id', 'products.sub_category_id'),
                    $direction
                );
                break;

            case 'seller':
                $query->orderBy(
                    \App\Models\User::select('name')
                        ->whereColumn('users.id', 'products.user_id'),
                    $direction
                );
                break;
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ATRIBUT DINAMIS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | DATA LISTING
    |--------------------------------------------------------------------------
    */

    public function getListings()
    {
        $query = $this->baseQuery()
            ->with([
                'category',
                'subCategory',
                'user',
                'store',
                'attributeValues',
                'primaryImage',
                'images',
                'location',
                'serviceDetail',
                'serviceAreas',
            ]);

        $query = $this->applySorting($query);

        $perPage = $this->perPage === 'all'
            ? max($query->count(), 1)
            : (int) $this->perPage;

        return $query->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL INFORMASI LISTING
    |--------------------------------------------------------------------------
    */

    public function openInfo(string $publicId, string $section): void
    {
        $allowedSections = [
            'specification',
            'seller',
            'store',
            'address',
            'photos',
            'category',
            'service',
            'service-areas',
            'complete',
        ];

        abort_unless(
            in_array($section, $allowedSections, true),
            404
        );

        $exists = $this->baseQuery()
            ->where('public_id', $publicId)
            ->exists();

        abort_unless($exists, 404);

        $this->selectedListingPublicId = $publicId;
        $this->activeInfo = $section;
    }

    public function getSelectedListing(): ?Product
    {
        if (! $this->selectedListingPublicId) {
            return null;
        }

        return Product::query()
            ->with([
                'category',
                'subCategory',
                'user.profile',
                'store',
                'images',
                'primaryImage',
                'location.province',
                'location.regency',
                'location.district',
                'location.village',
                'attributeValues.attribute.options',
                'serviceDetail',
                'serviceAreas.village',
                'serviceAreas.district',
                'serviceAreas.regency',
            ])
            ->where('public_id', $this->selectedListingPublicId)
            ->first();
    }

    public function getSelectedSpecifications()
    {
        $listing = $this->getSelectedListing();

        if (! $listing || ! $listing->sub_category_id) {
            return collect();
        }

        $mappings = SubCategoryAttribute::query()
            ->with('productAttribute.options')
            ->where('sub_category_id', $listing->sub_category_id)
            ->where('listing_type', $listing->listing_type)
            ->where('visible', true)
            ->orderBy('sort')
            ->get();

        return $mappings->map(function ($mapping) use ($listing) {
            $attribute = $mapping->productAttribute;

            if (! $attribute) {
                return null;
            }

            $answer = $listing->attributeValues
                ->firstWhere('product_attribute_id', $attribute->id);

            return [
                'name' => $attribute->name,
                'value' => $answer?->value ?: 'Belum diisi',
                'required' => $mapping->required,
            ];
        })->filter()->values();
    }

    public function closeInfo(): void
    {
        $this->selectedListingPublicId = null;
        $this->activeInfo = null;
    }

    /*
    |--------------------------------------------------------------------------
    | JUDUL HALAMAN
    |--------------------------------------------------------------------------
    */

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