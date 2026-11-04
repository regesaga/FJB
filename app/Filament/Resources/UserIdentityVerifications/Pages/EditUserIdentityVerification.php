<?php

namespace App\Filament\Resources\UserIdentityVerifications\Pages;

use App\Filament\Resources\UserIdentityVerifications\UserIdentityVerificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserIdentityVerification extends EditRecord
{
    protected static string $resource = UserIdentityVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
