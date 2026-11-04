<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ListBuyers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static ?string $navigationLabel = 'Pembeli';

    protected static string|UnitEnum|null $navigationGroup = 'Pengelolaan Pengguna';

    protected static ?int $navigationSort = 2;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public function getTitle(): string
    {
        return 'Daftar Pembeli';
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereIn('account_type', ['buyer', 'buyer_seller']);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}