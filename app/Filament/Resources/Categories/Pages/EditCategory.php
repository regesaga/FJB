<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Kategori')
                ->requiresConfirmation()
                ->modalHeading('Hapus Kategori')
                ->modalDescription('Kategori hanya dapat dihapus jika belum digunakan oleh subkategori, atribut, atau listing.')
                ->visible(fn (Category $record): bool =>
                    ! $record->subCategories()->exists()
                    && ! $record->categoryAttributes()->exists()
                    && ! $record->products()->exists()
                ),
        ];
    }
}