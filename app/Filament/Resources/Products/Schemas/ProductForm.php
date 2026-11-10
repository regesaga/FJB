<?php

namespace App\Filament\Resources\Products\Schemas;

use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use App\Models\User;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /*
            |--------------------------------------------------------------------------
            | INFORMASI PRODUK
            |--------------------------------------------------------------------------
            */

            Section::make('Informasi Produk')
                ->schema([

                    TextInput::make('title')
                        ->label('Nama Produk / Listing')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (
                            Set $set,
                            ?string $state
                        ) {
                            $set('slug', Str::slug($state ?? ''));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->readOnly(),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(5)
                        ->columnSpanFull(),

                ])
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | KATEGORI DAN SUBKATEGORI
            |--------------------------------------------------------------------------
            */

            Section::make('Kategori dan Subkategori')
                ->schema([

                    Select::make('category_id')
                        ->label('Kategori')
                        ->options(
                            fn () => Category::orderBy('name')
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('sub_category_id', null);
                            $set('attribute_values', []);
                        }),

                    Select::make('sub_category_id')
                        ->label('Subkategori')
                        ->options(function (Get $get) {

                            $categoryId = $get('category_id');

                            if (! $categoryId) {
                                return [];
                            }

                            return SubCategory::where(
                                'category_id',
                                $categoryId
                            )
                                ->orderBy('name')
                                ->pluck('name', 'id');

                        })
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(
                            fn (Set $set) => $set('attribute_values', [])
                        ),

                ])
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | SPESIFIKASI PRODUK
            |--------------------------------------------------------------------------
            */

            Section::make('Spesifikasi Produk')
                ->description(
                    'Pertanyaan akan menyesuaikan kategori dan subkategori yang dipilih.'
                )
                ->schema(function (Get $get): array {

                    $subCategoryId = $get('sub_category_id');

                    if (! $subCategoryId) {
                        return [

                            Textarea::make('specification_info')
                                ->label('Informasi')
                                ->default(
                                    'Pilih kategori dan subkategori terlebih dahulu.'
                                )
                                ->disabled()
                                ->dehydrated(false),

                        ];
                    }

                    $attributes = SubCategoryAttribute::query()
                        ->with('productAttribute.options')
                        ->where('sub_category_id', $subCategoryId)
                        ->where('visible', true)
                        ->orderBy('sort')
                        ->get();

                    return $attributes

                        ->filter(
                            fn ($mapping) =>
                                $mapping->productAttribute !== null
                        )

                        ->map(function ($mapping) {

                            $attribute = $mapping->productAttribute;

                            $name = 'attribute_values.' . $attribute->id;

                            $label = $attribute->name;

                            $required = $mapping->required
                                || $attribute->required;

                            $options = $attribute->options
                                ->sortBy('sort')
                                ->pluck('value', 'value')
                                ->toArray();

                            $field = match ($attribute->type) {

                                /*
                                | NUMBER
                                */

                                'number' => TextInput::make($name)
                                    ->label($label)
                                    ->numeric(),

                                /*
                                | TEXTAREA
                                */

                                'textarea' => Textarea::make($name)
                                    ->label($label)
                                    ->rows(3),

                                /*
                                | DATE
                                */

                                'date' => DatePicker::make($name)
                                    ->label($label),

                                /*
                                | SELECT
                                */

                                'select' => Select::make($name)
                                    ->label($label)
                                    ->options($options)
                                    ->native()
                                    ->live(),

                                /*
                                | RADIO
                                */

                                'radio' => Radio::make($name)
                                    ->label($label)
                                    ->options($options),

                                /*
                                | CHECKBOX
                                */

                                'checkbox' => CheckboxList::make($name)
                                    ->label($label)
                                    ->options($options)
                                    ->columns(2)

                                    // DEBUG: kondisi saat form pertama dibuka
                                    ->afterStateHydrated(function (
                                        $state
                                    ) use ($name, $options) {

                                        logger()->debug(
                                            'FJB CHECKBOX HYDRATED',
                                            [
                                                'field' => $name,
                                                'state' => $state,
                                                'options' => $options,
                                                'state_type' => gettype($state),
                                            ]
                                        );

                                    })

                                    // DEBUG: kondisi setelah checkbox berubah
                                    ->afterStateUpdated(function (
                                        $state
                                    ) use ($name, $options) {

                                        logger()->debug(
                                            'FJB CHECKBOX UPDATED',
                                            [
                                                'field' => $name,
                                                'state' => $state,
                                                'options' => $options,
                                                'state_type' => gettype($state),
                                            ]
                                        );

                                    }),

                                /*
                                | DEFAULT TEXT INPUT
                                */

                                default => TextInput::make($name)
                                    ->label($label),

                            };

                            return $required
                                ? $field->required()
                                : $field->nullable();

                        })

                        ->values()
                        ->all();

                })
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | HARGA
            |--------------------------------------------------------------------------
            */

            Section::make('Harga')
                ->schema([

                    TextInput::make('price')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->nullable(),

                    Select::make('price_type')
                        ->label('Jenis Harga')
                        ->options([

                            'fixed' => 'Harga Tetap',
                            'negotiable' => 'Bisa Nego',
                            'starting_from' => 'Harga Mulai Dari',
                            'estimated' => 'Harga Perkiraan',
                            'contact' => 'Hubungi Penjual',

                        ])
                        ->default('fixed')
                        ->required()
                        ->native(false),

                ])
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | PENJUAL DAN PUBLIKASI
            |--------------------------------------------------------------------------
            */

            Section::make('Penjual dan Publikasi')
                ->schema([

                    Select::make('user_id')
                        ->label('Penjual')
                        ->options(function (Get $get) {

                            $currentUserId = auth()->id();

                            $selectedUserId = $get('user_id');

                            return User::query()

                                ->where(function ($query) use (
                                    $currentUserId,
                                    $selectedUserId
                                ) {

                                    $query->where(
                                        'account_type',
                                        'seller'
                                    );

                                    if ($currentUserId) {
                                        $query->orWhereKey($currentUserId);
                                    }

                                    if ($selectedUserId) {
                                        $query->orWhereKey($selectedUserId);
                                    }

                                })

                                ->orderBy('name')
                                ->get()

                                ->mapWithKeys(fn (User $user) => [

                                    $user->id => $user->name
                                        . ' (' . $user->email . ')',

                                ])

                                ->toArray();

                        })
                        ->default(fn () => auth()->id())
                        ->searchable()
                        ->required()
                        ->native(false),

                    Select::make('status')
                        ->label('Status Publikasi')
                        ->options([

                            'draft' => 'Draft',
                            'published' => 'Published',

                        ])
                        ->default('draft')
                        ->required()
                        ->native(false),

                ])
                ->columns(2),

        ]);
    }
}