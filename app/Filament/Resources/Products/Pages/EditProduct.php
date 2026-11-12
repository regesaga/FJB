<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\SubCategoryAttribute;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\Url;
use App\Models\Category;
use App\Models\SubCategory;
class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $attributeValues = [];

    protected array $locationData = [];

    /*
    |--------------------------------------------------------------------------
    | FILTER YANG DIBAWA DARI MANAJEMEN LISTING
    |--------------------------------------------------------------------------
    */

   #[Url(as: 'category')]
public ?string $categorySlug = null;

#[Url(as: 'subcategory')]
public ?string $subCategorySlug = null;

#[Url]
public ?string $listingType = null;

    /*
    |--------------------------------------------------------------------------
    | HEADER ACTIONS
    |--------------------------------------------------------------------------
    */

    protected function getHeaderActions(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD DATA ATRIBUT DAN LOKASI SAAT EDIT
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $values = [];

        // Muat atribut beserta pilihan.
        $this->record->load('attributeValues.attribute.options');

        foreach ($this->record->attributeValues as $item) {

            $value = $item->value;

            if ($item->attribute?->type === 'checkbox') {

                $decoded = is_array($value)
                    ? $value
                    : json_decode((string) $value, true);

                $allowedOptions = $item->attribute->options
                    ->pluck('value')
                    ->map(fn ($option) => (string) $option)
                    ->all();

                if (! is_array($decoded)) {
                    $decoded = is_scalar($decoded)
                        ? [$decoded]
                        : [];
                }

                $value = array_values(
                    array_filter(
                        $decoded,
                        fn ($selected) =>
                            is_scalar($selected)
                            && in_array(
                                (string) $selected,
                                $allowedOptions,
                                true
                            )
                    )
                );
            }

            $values[$item->product_attribute_id] = $value;
        }

        $data['attribute_values'] = $values;

        /*
        |--------------------------------------------------------------------------
        | LOAD LOKASI PRODUK
        |--------------------------------------------------------------------------
        */

        $location = $this->record->location;

        if ($location) {

            $data['province_code'] = $location->province_code;
            $data['regency_code'] = $location->regency_code;
            $data['district_code'] = $location->district_code;
            $data['village_code'] = $location->village_code;
            $data['address_detail'] = $location->address_detail;
            $data['latitude'] = $location->latitude;
            $data['longitude'] = $location->longitude;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | PERSIAPAN DATA SEBELUM UPDATE
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan atribut sementara.
        $this->attributeValues = $data['attribute_values'] ?? [];

        unset($data['attribute_values']);

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA LOKASI
        |--------------------------------------------------------------------------
        */

        $this->locationData = [
            'province_code' => $data['province_code'] ?? null,
            'regency_code' => $data['regency_code'] ?? null,
            'district_code' => $data['district_code'] ?? null,
            'village_code' => $data['village_code'] ?? null,
            'address_detail' => $data['address_detail'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ];

        // Hapus field lokasi dari data utama produk.
        unset(
            $data['province_code'],
            $data['regency_code'],
            $data['district_code'],
            $data['village_code'],
            $data['address_detail'],
            $data['latitude'],
            $data['longitude']
        );

        // Pastikan user tetap terisi.
        if (empty($data['user_id'])) {
            $data['user_id'] = $this->record->user_id;
        }

        // Atur tanggal publikasi.
        if (($data['status'] ?? 'draft') === 'published') {

            $data['published_at'] =
                $this->record->published_at ?? now();

        } else {

            $data['published_at'] = null;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN ATRIBUT DAN LOKASI SETELAH UPDATE
    |--------------------------------------------------------------------------
    */

    protected function afterSave(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SIMPAN ATRIBUT PRODUK
        |--------------------------------------------------------------------------
        */

        $mappedAttributes = SubCategoryAttribute::query()
            ->where('sub_category_id', $this->record->sub_category_id)
            ->where('visible', true)
            ->pluck('product_attribute_id');

        foreach ($mappedAttributes as $attributeId) {

            $value = $this->attributeValues[$attributeId] ?? null;

            // Hapus nilai jika kosong.
            if (
                $value === null ||
                $value === '' ||
                $value === []
            ) {
                $this->record->attributeValues()
                    ->where('product_attribute_id', $attributeId)
                    ->delete();

                continue;
            }

            $this->record->attributeValues()->updateOrCreate(
                [
                    'product_attribute_id' => $attributeId,
                ],
                [
                    'value' => is_array($value)
                        ? json_encode($value, JSON_UNESCAPED_UNICODE)
                        : (string) $value,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE LOKASI
        |--------------------------------------------------------------------------
        */

        if (! empty($this->locationData['province_code'])) {

            $this->record->location()->updateOrCreate(
                [
                    'product_id' => $this->record->id,
                ],
                $this->locationData
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI SETELAH UPDATE
    |--------------------------------------------------------------------------
    */

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Listing berhasil diperbarui';
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT SETELAH EDIT
    |--------------------------------------------------------------------------
    */

    protected function getRedirectUrl(): string
{
    // Prioritaskan filter dari halaman sebelumnya.
    // Jika tidak ada, gunakan kategori listing yang sedang diedit.
    $categorySlug = $this->categorySlug
        ?: Category::whereKey($this->record->category_id)
            ->value('slug');

    $subCategorySlug = $this->subCategorySlug
        ?: SubCategory::whereKey($this->record->sub_category_id)
            ->value('slug');

    $listingType = $this->listingType
        ?: Category::whereKey($this->record->category_id)
            ->value('listing_type');

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