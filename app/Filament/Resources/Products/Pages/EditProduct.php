<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\SubCategoryAttribute;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $attributeValues = [];

    protected function getHeaderActions(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD DATA ATRIBUT SAAT EDIT
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $values = [];

        // Muat atribut beserta pilihan yang tersedia.
        $this->record->load('attributeValues.attribute.options');

        foreach ($this->record->attributeValues as $item) {

            $value = $item->value;

            if ($item->attribute?->type === 'checkbox') {

                // Ambil nilai JSON dari database.
                $decoded = is_array($value)
                    ? $value
                    : json_decode((string) $value, true);

                // Ambil pilihan checkbox yang valid.
                $allowedOptions = $item->attribute->options
                    ->pluck('value')
                    ->map(fn ($option) => (string) $option)
                    ->all();

                // Tangani data lama yang hanya menyimpan satu nilai.
                if (! is_array($decoded)) {
                    $decoded = is_scalar($decoded)
                        ? [$decoded]
                        : [];
                }

                // Buang nilai yang tidak tersedia dalam opsi.
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

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | PERSIAPAN DATA SEBELUM UPDATE
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->attributeValues = $data['attribute_values'] ?? [];

        unset($data['attribute_values']);

        // Pastikan penjual terisi.
        if (empty($data['user_id'])) {
            $data['user_id'] = $this->record->user_id;
        }

        // Atur tanggal publikasi berdasarkan status.
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
    | SIMPAN PERUBAHAN ATRIBUT
    |--------------------------------------------------------------------------
    */

    protected function afterSave(): void
    {
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
    | REDIRECT SETELAH UPDATE
    |--------------------------------------------------------------------------
    */

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.pages.listing-manager');
    }
}