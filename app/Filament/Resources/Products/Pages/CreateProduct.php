<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Category;
use App\Models\SubCategory;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected array $attributeValues = [];

    protected array $locationData = [];

    public ?int $returnCategoryId = null;

    public ?int $returnSubCategoryId = null;

    public ?string $returnListingType = null;

    /*
    |--------------------------------------------------------------------------
    | LABEL LISTING
    |--------------------------------------------------------------------------
    */

    protected function getListingLabel(): string
    {
        return match ($this->returnListingType) {
            'service' => 'Jasa',
            'other' => 'Lainnya',
            default => 'Produk',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | JUDUL HALAMAN
    |--------------------------------------------------------------------------
    */

    public function getTitle(): string
    {
        return 'Buat ' . $this->getListingLabel();
    }

    /*
    |--------------------------------------------------------------------------
    | MOUNT FORM
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        parent::mount();

        $categorySlug = request()->query('category');
        $subCategorySlug = request()->query('subcategory');

        $category = $categorySlug
            ? Category::query()
                ->where('slug', $categorySlug)
                ->where('is_active', true)
                ->first()
            : null;

        if ($category) {
            $this->returnCategoryId = (int) $category->id;

            $this->returnListingType = $category->listing_type;

            $this->data['category_id'] = $category->id;

            $subCategory = $subCategorySlug
                ? SubCategory::query()
                    ->where('slug', $subCategorySlug)
                    ->where('category_id', $category->id)
                    ->where('is_active', true)
                    ->first()
                : null;

            if ($subCategory) {
                $this->returnSubCategoryId = (int) $subCategory->id;

                $this->data['sub_category_id'] = $subCategory->id;
            }
        } else {
            $type = request()->query('listingType');

            $this->returnListingType = in_array(
                $type,
                ['product', 'service', 'other'],
                true
            ) ? $type : 'product';
        }

        $this->form->fill($this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | PERSIAPAN DATA SEBELUM CREATE
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan atribut sementara.
        $this->attributeValues = $data['attribute_values'] ?? [];

        unset($data['attribute_values']);

        // Ambil data lokasi.
        $this->locationData = [
            'province_code' => $data['province_code'] ?? null,
            'regency_code' => $data['regency_code'] ?? null,
            'district_code' => $data['district_code'] ?? null,
            'village_code' => $data['village_code'] ?? null,
            'address_detail' => $data['address_detail'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ];

        // Lokasi tidak disimpan di tabel products.
        unset(
            $data['province_code'],
            $data['regency_code'],
            $data['district_code'],
            $data['village_code'],
            $data['address_detail'],
            $data['latitude'],
            $data['longitude']
        );

        // Pastikan user terisi.
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $data['status'] = 'draft';
        $data['published_at'] = null;

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN ATRIBUT DAN LOKASI SETELAH CREATE
    |--------------------------------------------------------------------------
    */

    protected function afterCreate(): void
    {
        // Simpan atribut listing.
        foreach ($this->attributeValues as $attributeId => $value) {

            if (
                $value === null ||
                $value === '' ||
                $value === []
            ) {
                continue;
            }

            $this->record->attributeValues()->create([
                'product_attribute_id' => $attributeId,
                'value' => is_array($value)
                    ? json_encode($value, JSON_UNESCAPED_UNICODE)
                    : (string) $value,
            ]);
        }

        // Simpan lokasi listing.
        if (! empty($this->locationData['province_code'])) {
            $this->record->location()->create($this->locationData);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getListingLabel() . ' berhasil disimpan sebagai Draft';
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT SETELAH CREATE
    |--------------------------------------------------------------------------
    */

    protected function getRedirectUrl(): string
    {
        $categoryId = $this->returnCategoryId
            ?? $this->record->category_id;

        $subCategoryId = $this->returnCategoryId !== null
            ? $this->returnSubCategoryId
            : $this->record->sub_category_id;

        $categorySlug = $categoryId
            ? Category::whereKey($categoryId)->value('slug')
            : null;

        $subCategorySlug = $subCategoryId
            ? SubCategory::whereKey($subCategoryId)->value('slug')
            : null;

        $listingType = $this->returnListingType;

        if (! $listingType && $categoryId) {
            $listingType = Category::whereKey($categoryId)
                ->value('listing_type');
        }

        return route(
            'filament.admin.pages.listing-manager',
            array_filter([
                'category' => $categorySlug,
                'subcategory' => $subCategorySlug,
                'listingType' => $listingType,
            ], fn ($value) => $value !== null && $value !== '')
        );
    }
}