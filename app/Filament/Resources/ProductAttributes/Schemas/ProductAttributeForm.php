<?php

namespace App\Filament\Resources\ProductAttributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Atribut / Pertanyaan')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('type')
                    ->label('Tipe Pertanyaan')
                    ->options([
                        'text' => 'Text - Jawaban Singkat',
                        'number' => 'Number - Angka',
                        'textarea' => 'Textarea - Jawaban Panjang',
                        'date' => 'Date - Tanggal',
                        'select' => 'Select - Dropdown',
                        'checkbox' => 'Checkbox - Pilihan Ganda',
                        'radio' => 'Radio - Pilihan Tunggal',
                    ])
                    ->required()
                    ->native(false),

                Toggle::make('required')
                    ->label('Wajib Diisi')
                    ->default(false),

                Toggle::make('filterable')
                    ->label('Dapat Digunakan Sebagai Filter')
                    ->default(true),

                Toggle::make('searchable')
                    ->label('Dapat Dicari')
                    ->default(true),

                TextInput::make('sort')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}