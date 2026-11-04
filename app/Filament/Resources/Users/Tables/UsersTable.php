<?php

namespace App\Filament\Resources\Users\Tables;
use Illuminate\Support\Facades\DB;
use App\Models\UserSuspensionLog;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('account_type')
                ->label('Jenis Pengguna')
                ->badge()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'administrator' => 'Administrator',
                    'buyer' => 'Pembeli',
                    'seller' => 'Penjual',
                    'buyer_seller' => 'Penjual & Pembeli',
                    default => 'Tidak Diketahui',
                })
                ->color(fn ($state) => match ($state) {
                    'administrator' => 'danger',
                    'buyer' => 'info',
                    'seller' => 'success',
                    'buyer_seller' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('profile.phone')
                    ->label('Nomor HP')
                    ->placeholder('-'),

                TextColumn::make('store.name')
                    ->label('Nama Toko')
                    ->placeholder('Belum memiliki toko'),

                TextColumn::make('products_count')
                    ->label('Jumlah Iklan')
                    ->sortable(),

                TextColumn::make('identityVerification.status')
                    ->label('Verifikasi Identitas')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'verified' => 'Terverifikasi',
                        'pending' => 'Menunggu',
                        'rejected' => 'Ditolak',
                        default => 'Belum Mengajukan',
                    })
                    ->color(fn ($state) => match ($state) {
                        'verified' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('suspended_at')
                    ->label('Status Akun')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state
                        ? 'Ditangguhkan'
                        : 'Aktif')
                    ->color(fn ($state) => $state ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label('Tanggal Bergabung')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
    SelectFilter::make('account_type')
        ->label('Jenis Pengguna')
        ->options([
            'administrator' => 'Administrator',
            'buyer' => 'Pembeli',
            'seller' => 'Penjual',
            'buyer_seller' => 'Penjual & Pembeli',
        ]),

                SelectFilter::make('account_status')
                    ->label('Status Akun')
                    ->options([
                        'active' => 'Aktif',
                        'suspended' => 'Ditangguhkan',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'active' => $query->whereNull('suspended_at'),
                            'suspended' => $query->whereNotNull('suspended_at'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
    ViewAction::make()
        ->label('Detail'),

    EditAction::make()
        ->label('Edit'),

    Action::make('suspend')
        ->label('Suspend')
        ->color('danger')
        ->visible(fn (User $record): bool =>
            auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) &&
            ! $record->isSuspended() &&
            ! $record->hasAnyRole(['Super Admin', 'Admin', 'Moderator']) &&
            $record->account_type !== 'administrator' &&
            auth()->id() !== $record->id
        )
        ->schema([
            Textarea::make('reason')
                ->label('Alasan Penangguhan')
                ->required()
                ->minLength(5)
                ->maxLength(2000),
        ])
        ->modalHeading('Tangguhkan Akun Pengguna')
        ->modalSubmitActionLabel('Tangguhkan Akun')
        ->action(function (User $record, array $data): void {
    $admin = auth()->user();

    abort_unless(
        $admin && $admin->hasAnyRole(['Super Admin', 'Admin']),
        403
    );

    abort_if(
        $record->isSuspended() ||
        $record->hasAnyRole(['Super Admin', 'Admin', 'Moderator']) ||
        $record->account_type === 'administrator' ||
        $admin->id === $record->id,
        403
    );

    DB::transaction(function () use ($record, $data, $admin): void {
        $record->forceFill([
            'suspended_at' => now(),
            'suspension_reason' => $data['reason'],
            'suspended_by' => $admin->id,
        ])->save();

        UserSuspensionLog::create([
            'user_id' => $record->id,
            'admin_id' => $admin->id,
            'action' => 'suspended',
            'reason' => $data['reason'],
        ]);
    });

    Notification::make()
        ->title('Akun berhasil ditangguhkan')
        ->success()
        ->send();
}),

    Action::make('reactivate')
        ->label('Aktifkan Kembali')
        ->color('success')
        ->visible(fn (User $record): bool =>
            auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) &&
            $record->isSuspended() &&
            ! $record->hasAnyRole(['Super Admin', 'Admin', 'Moderator']) &&
            $record->account_type !== 'administrator'
        )
        ->requiresConfirmation()
        ->modalHeading('Aktifkan Kembali Akun')
        ->modalDescription('Apakah Anda yakin ingin mengaktifkan kembali akun ini?')
        ->modalSubmitActionLabel('Aktifkan')
        ->action(function (User $record): void {
    $admin = auth()->user();

    abort_unless(
        $admin && $admin->hasAnyRole(['Super Admin', 'Admin']),
        403
    );

    abort_unless(
        $record->isSuspended() &&
        ! $record->hasAnyRole(['Super Admin', 'Admin', 'Moderator']) &&
        $record->account_type !== 'administrator',
        403
    );

    DB::transaction(function () use ($record, $admin): void {
        UserSuspensionLog::create([
            'user_id' => $record->id,
            'admin_id' => $admin->id,
            'action' => 'reactivated',
            'reason' => 'Akun diaktifkan kembali oleh administrator.',
        ]);

        $record->forceFill([
            'suspended_at' => null,
            'suspension_reason' => null,
            'suspended_by' => null,
        ])->save();
    });

    Notification::make()
        ->title('Akun berhasil diaktifkan kembali')
        ->success()
        ->send();
}),
])
            ->toolbarActions([]);
    }
}