<?php

namespace App\Filament\Resources\Products\Schemas;

use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use App\Models\User;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

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
    /**
     * Menentukan jenis listing dari URL atau kategori.
     */
    private static function listingType(): string
    {
        $type = request()->query('listingType');

        if (in_array($type, ['product', 'service', 'other'], true)) {
            return $type;
        }

        $categorySlug = request()->query('category');

        if ($categorySlug) {
            $type = Category::where('slug', $categorySlug)
                ->value('listing_type');

            if (in_array($type, ['product', 'service', 'other'], true)) {
                return $type;
            }
        }

        return 'product';
    }

    /**
     * Label berdasarkan jenis listing.
     */
    private static function listingLabel(): string
    {
        return match (self::listingType()) {
            'service' => 'Jasa',
            'other' => 'Lainnya',
            default => 'Produk',
        };
    }

    /**
     * Mengambil kategori dari slug URL.
     */
    private static function selectedCategoryId(): ?int
    {
        $slug = request()->query('category');

        if (! $slug) {
            return null;
        }

        $id = Category::query()
            ->where('slug', $slug)
            ->where('listing_type', self::listingType())
            ->where('is_active', true)
            ->value('id');

        return $id ? (int) $id : null;
    }

    /**
     * Mengambil subkategori dari slug URL.
     */
    private static function selectedSubCategoryId(): ?int
    {
        $slug = request()->query('subcategory');
        $categoryId = self::selectedCategoryId();

        if (! $slug || ! $categoryId) {
            return null;
        }

        $id = SubCategory::query()
            ->where('slug', $slug)
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->value('id');

        return $id ? (int) $id : null;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /*
            |--------------------------------------------------------------------------
            | INFORMASI LISTING
            |--------------------------------------------------------------------------
            */

            Section::make(fn () => 'Informasi ' . self::listingLabel())
                ->schema([

                    TextInput::make('title')
                        ->label(fn () => 'Nama ' . self::listingLabel())
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
    ->options(function (Get $get) {
        $categoryId = $get('category_id');

        // Jika kategori sudah ditentukan, tampilkan kategori tersebut saja.
        if ($categoryId) {
            return Category::whereKey($categoryId)
                ->pluck('name', 'id')
                ->toArray();
        }

        // Jika belum ditentukan, tampilkan kategori sesuai jenis listing.
        return Category::query()
            ->where('listing_type', self::listingType())
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->default(fn () => self::selectedCategoryId())
    ->disabled(function (string $operation) {
        // Kategori terkunci saat edit.
        if ($operation === 'edit') {
            return true;
        }

        // Kategori terkunci saat tambah dari kategori sidebar.
        return self::selectedCategoryId() !== null;
    })
    ->dehydrated()
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

        return SubCategory::query()
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    })
    ->default(fn () => self::selectedSubCategoryId())
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
            | LOKASI LISTING
            |--------------------------------------------------------------------------
            */

            Section::make(fn () => 'Lokasi ' . self::listingLabel())
                ->description('Tentukan lokasi barang atau jasa yang ditawarkan.')
                ->schema([

                    Select::make('province_code')
                        ->label('Provinsi')
                        ->options(
                            fn () => Province::orderBy('name')
                                ->pluck('name', 'code')
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('regency_code', null);
                            $set('district_code', null);
                            $set('village_code', null);
                        }),

                    Select::make('regency_code')
                        ->label('Kabupaten / Kota')
                        ->options(fn (Get $get) => $get('province_code')
                            ? Regency::where(
                                'province_code',
                                $get('province_code')
                            )
                                ->orderBy('name')
                                ->pluck('name', 'code')
                            : []
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => ! $get('province_code'))
                        ->afterStateUpdated(function (Set $set) {
                            $set('district_code', null);
                            $set('village_code', null);
                        }),

                    Select::make('district_code')
                        ->label('Kecamatan')
                        ->options(fn (Get $get) => $get('regency_code')
                            ? District::where(
                                'regency_code',
                                $get('regency_code')
                            )
                                ->orderBy('name')
                                ->pluck('name', 'code')
                            : []
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => ! $get('regency_code'))
                        ->afterStateUpdated(
                            fn (Set $set) => $set('village_code', null)
                        ),

                    Select::make('village_code')
                        ->label('Desa / Kelurahan')
                        ->options(fn (Get $get) => $get('district_code')
                            ? Village::where(
                                'district_code',
                                $get('district_code')
                            )
                                ->orderBy('name')
                                ->pluck('name', 'code')
                            : []
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => ! $get('district_code'))
                        ->afterStateUpdated(function ($state, Set $set) {

                            $village = Village::where('code', $state)
                                ->first();

                            if ($village) {
                                $set('latitude', $village->latitude);
                                $set('longitude', $village->longitude);
                            }

                        }),

                    Textarea::make('address_detail')
                        ->label('Alamat Detail / Patokan')
                        ->rows(3)
                        ->columnSpanFull(),

                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->numeric(),

                    TextInput::make('longitude')
                        ->label('Longitude')
                        ->numeric(),

                ])
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | SPESIFIKASI DINAMIS
            |--------------------------------------------------------------------------
            */

            Section::make(fn () => 'Spesifikasi ' . self::listingLabel())
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

                                // NUMBER
                                'number' => TextInput::make($name)
                                    ->label($label)
                                    ->numeric(),

                                // TEXTAREA
                                'textarea' => Textarea::make($name)
                                    ->label($label)
                                    ->rows(3),

                                // DATE
                                'date' => DatePicker::make($name)
                                    ->label($label),

                                // SELECT
                                'select' => Select::make($name)
                                    ->label($label)
                                    ->options($options)
                                    ->native()
                                    ->live(),

                                // RADIO
                                'radio' => Radio::make($name)
                                    ->label($label)
                                    ->options($options),

                                // CHECKBOX
                                'checkbox' => CheckboxList::make($name)
                                    ->label($label)
                                    ->options($options)
                                    ->columns(2)

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

                                // DEFAULT TEXT INPUT
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