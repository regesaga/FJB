<?php

namespace App\Filament\Resources\UserIdentityVerifications\Pages;

use App\Filament\Resources\UserIdentityVerifications\UserIdentityVerificationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUserIdentityVerification extends ViewRecord
{
    protected static string $resource = UserIdentityVerificationResource::class;

    public function mount(int|string $record): void
    {
        abort_unless(
            auth()->user()?->can('identity_verifications.view'),
            403
        );

        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}