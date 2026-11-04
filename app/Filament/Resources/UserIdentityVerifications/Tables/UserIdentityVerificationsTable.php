<?php

namespace App\Filament\Resources\UserIdentityVerifications\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserIdentityVerificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.profile.phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('identity_type')
                    ->label('Jenis Identitas')
                    ->badge(),

                TextColumn::make('face_match_status')
                    ->label('Pencocokan Wajah')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified', 'passed' => 'success',
                        'rejected', 'failed' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('liveness_status')
                    ->label('Liveness')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified', 'passed' => 'success',
                        'rejected', 'failed' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('status')
                    ->label('Status Pengajuan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}