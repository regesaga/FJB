<?php

namespace App\Filament\Resources\UserIdentityVerifications;
use UnitEnum;
use App\Filament\Resources\UserIdentityVerifications\Pages\ListUserIdentityVerifications;
use App\Filament\Resources\UserIdentityVerifications\Tables\UserIdentityVerificationsTable;
use App\Filament\Resources\UserIdentityVerifications\Pages\ViewUserIdentityVerification;
use App\Models\UserIdentityVerification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserIdentityVerificationResource extends Resource
{
    protected static ?string $model = UserIdentityVerification::class;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Verifikasi Identitas';

    protected static ?string $modelLabel = 'Verifikasi Identitas';

    protected static ?string $pluralModelLabel = 'Verifikasi Identitas';

    protected static string|UnitEnum|null $navigationGroup = 'Keamanan & Kredibilitas';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return UserIdentityVerificationsTable::configure($table);
    }
public static function canViewAny(): bool
{
    return auth()->user()?->can('identity_verifications.view') ?? false;
}
    public static function getPages(): array
{
    return [
        'index' => ListUserIdentityVerifications::route('/'),
        'view' => ViewUserIdentityVerification::route('/{record}'),
    ];
}
}
