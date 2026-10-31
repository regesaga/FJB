<?php

namespace App\Filament\Resources\SubCategories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori Induk')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Subkategori')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('icon')
                    ->label('Ikon')
                    ->maxLength(255)
                    ->placeholder('Contoh: heroicon-o-car'),

                TextInput::make('sort')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}