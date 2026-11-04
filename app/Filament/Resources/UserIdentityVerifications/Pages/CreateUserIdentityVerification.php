<?php

namespace App\Filament\Resources\UserIdentityVerifications\Pages;

use App\Filament\Resources\UserIdentityVerifications\UserIdentityVerificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserIdentityVerification extends CreateRecord
{
    protected static string $resource = UserIdentityVerificationResource::class;
}
