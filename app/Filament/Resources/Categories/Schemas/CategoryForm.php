<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Elektronik')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        $set('slug', Str::slug($state ?? ''));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('Contoh: elektronik')
                    ->helperText('Slug harus unik dan menggunakan format URL.'),

                TextInput::make('icon')
                    ->label('Nama Ikon')
                    ->maxLength(255)
                    ->placeholder('Contoh: heroicon-o-device-phone-mobile'),

                FileUpload::make('image')
                    ->label('Gambar Kategori')
                    ->image()
                    ->disk('public')
                    ->directory('categories')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(2048),

                TextInput::make('sort')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->integer()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Kategori Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}