<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ListSellers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static ?string $navigationLabel = 'Penjual';

    protected static string|UnitEnum|null $navigationGroup = 'Pengelolaan Pengguna';

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public function getTitle(): string
    {
        return 'Daftar Penjual';
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereIn('account_type', ['seller', 'buyer_seller']);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}