<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Detail Pengguna';
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Akun')
                ->columns(2)
                ->schema([
                    TextEntry::make('name')
                        ->label('Nama Pengguna'),

                    TextEntry::make('email')
                        ->label('Email'),

                    TextEntry::make('account_type')
                        ->label('Jenis Pengguna')
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'administrator' => 'Administrator',
                            'buyer' => 'Pembeli',
                            'seller' => 'Penjual',
                            'buyer_seller' => 'Penjual & Pembeli',
                            default => 'Tidak Diketahui',
                        }),

                    TextEntry::make('created_at')
                        ->label('Tanggal Bergabung')
                        ->dateTime('d M Y H:i'),
                ]),

            Section::make('Profil Pengguna')
                ->columns(2)
                ->schema([
                    TextEntry::make('profile.full_name')
                        ->label('Nama Lengkap')
                        ->placeholder('-'),

                    TextEntry::make('profile.phone')
                        ->label('Nomor HP')
                        ->placeholder('-'),

                    TextEntry::make('profile.birth_date')
                        ->label('Tanggal Lahir')
                        ->date('d M Y')
                        ->placeholder('-'),

                    TextEntry::make('profile.gender')
                        ->label('Jenis Kelamin')
                        ->placeholder('-'),

                    TextEntry::make('profile.bio')
                        ->label('Biografi')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),

            Section::make('Informasi Toko')
                ->columns(2)
                ->schema([
                    TextEntry::make('store.name')
                        ->label('Nama Toko')
                        ->placeholder('Belum memiliki toko'),

                    TextEntry::make('store.verified')
                        ->label('Status Verifikasi Toko')
                        ->formatStateUsing(fn ($state) => $state ? 'Terverifikasi' : 'Belum Terverifikasi')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'warning'),

                    TextEntry::make('store.phone')
                        ->label('Telepon Toko')
                        ->placeholder('-'),

                    TextEntry::make('store.address')
                        ->label('Alamat Toko')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),

            Section::make('Aktivitas Iklan')
                ->schema([
                    TextEntry::make('products_count')
                        ->label('Total Iklan')
                        ->numeric(),
                ]),

            Section::make('Verifikasi Identitas')
                ->schema([
                    TextEntry::make('identityVerification.status')
                        ->label('Status Verifikasi')
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'verified' => 'Terverifikasi',
                            'pending' => 'Menunggu Pemeriksaan',
                            'rejected' => 'Ditolak',
                            default => 'Belum Mengajukan',
                        })
                        ->badge(),
                ]),

            Section::make('Status Akun')
                ->columns(2)
                ->schema([
                    TextEntry::make('suspended_at')
                        ->label('Status')
                        ->formatStateUsing(fn ($state) => $state ? 'Ditangguhkan' : 'Aktif')
                        ->badge()
                        ->color(fn ($state) => $state ? 'danger' : 'success'),

                    TextEntry::make('suspension_reason')
                        ->label('Alasan Penangguhan')
                        ->placeholder('Tidak ada')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}