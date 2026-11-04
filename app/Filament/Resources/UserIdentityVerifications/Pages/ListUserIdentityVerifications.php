<?php

namespace App\Filament\Resources\UserIdentityVerifications\Pages;

use App\Filament\Resources\UserIdentityVerifications\UserIdentityVerificationResource;
use Filament\Resources\Pages\ListRecords;

class ListUserIdentityVerifications extends ListRecords
{
    protected static string $resource = UserIdentityVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}