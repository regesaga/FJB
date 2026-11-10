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

    public function mount(): void
    {
        parent::mount();

        $categoryId = request()->query('category_id');
        $subCategoryId = request()->query('sub_category_id');

        // Pastikan kategori benar-benar tersedia.
        if (
            $categoryId &&
            Category::whereKey($categoryId)->exists()
        ) {
            $this->data['category_id'] = (int) $categoryId;

            // Pastikan subkategori termasuk kategori tersebut.
            if (
                $subCategoryId &&
                SubCategory::where('id', $subCategoryId)
                    ->where('category_id', $categoryId)
                    ->exists()
            ) {
                $this->data['sub_category_id'] = (int) $subCategoryId;
            }

            // Isi ulang form dengan data yang sudah ditentukan.
            $this->form->fill($this->data);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->attributeValues = $data['attribute_values'] ?? [];

        unset($data['attribute_values']);

        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $data['status'] = 'draft';
        $data['published_at'] = null;

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->attributeValues as $attributeId => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $this->record->attributeValues()->create([
                'product_attribute_id' => $attributeId,
                'value' => is_array($value)
                    ? json_encode($value, JSON_UNESCAPED_UNICODE)
                    : (string) $value,
            ]);
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Listing berhasil disimpan sebagai Draft';
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.pages.listing-manager');
    }
}