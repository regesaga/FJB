<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSubCategoryAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'kendaraan')->firstOrFail();

        $subcategories = [

            'motor' => [

                [
                    'name' => 'Jenis Motor',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Matic',
                        'Bebek',
                        'Sport',
                        'Trail',
                        'Cub',
                        'Listrik',
                        'Lainnya',
                    ],
                ],

                [
                    'name' => 'Kapasitas Mesin (CC)',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Status Pajak',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Aktif',
                        'Mati',
                    ],
                ],

                [
                    'name' => 'Kelengkapan Surat',
                    'type' => 'checkbox',
                    'required' => false,
                    'options' => [
                        'STNK',
                        'BPKB',
                    ],
                ],

                [
                    'name' => 'Sistem Starter',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Elektrik',
                        'Kick Starter',
                        'Elektrik dan Kick Starter',
                    ],
                ],

            ],

            'mobil' => [

                [
                    'name' => 'Jenis Mobil',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Sedan',
                        'Hatchback',
                        'MPV',
                        'SUV',
                        'Crossover',
                        'Pickup',
                        'Minibus',
                        'Lainnya',
                    ],
                ],

                [
                    'name' => 'Kapasitas Mesin (CC)',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Jumlah Pintu',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        '2',
                        '3',
                        '4',
                        '5',
                    ],
                ],

                [
                    'name' => 'Kapasitas Penumpang',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Sistem Penggerak',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'FWD',
                        'RWD',
                        'AWD',
                        '4WD',
                    ],
                ],

            ],
                        'sepeda' => [

                [
                    'name' => 'Jenis Sepeda',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'MTB',
                        'Road Bike',
                        'BMX',
                        'Lipat',
                        'City Bike',
                        'Hybrid',
                        'Sepeda Anak',
                        'Listrik',
                        'Lainnya',
                    ],
                ],

                [
                    'name' => 'Ukuran Roda',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        '12 inci',
                        '16 inci',
                        '20 inci',
                        '24 inci',
                        '26 inci',
                        '27.5 inci',
                        '29 inci',
                    ],
                ],

                [
                    'name' => 'Bahan Frame',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Aluminium',
                        'Steel',
                        'Carbon',
                        'Titanium',
                    ],
                ],

                [
                    'name' => 'Jenis Rem',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'V-Brake',
                        'Mechanical Disc',
                        'Hydraulic Disc',
                        'Coaster',
                    ],
                ],

                [
                    'name' => 'Jumlah Kecepatan (Speed)',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Jenis Suspensi',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Rigid',
                        'Hardtail',
                        'Full Suspension',
                    ],
                ],

            ],
                        'truk' => [

                [
                    'name' => 'Jenis Truk',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Pickup',
                        'Engkel',
                        'Double Engkel',
                        'Tronton',
                        'Trailer',
                        'Dump Truck',
                        'Box',
                        'Tangki',
                        'Wingbox',
                        'Lainnya',
                    ],
                ],

                [
                    'name' => 'Kapasitas Muatan',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Satuan Kapasitas Muatan',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Kg',
                        'Ton',
                    ],
                ],

                [
                    'name' => 'Konfigurasi Sumbu',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        '4x2',
                        '4x4',
                        '6x2',
                        '6x4',
                        '8x2',
                        '8x4',
                    ],
                ],

                [
                    'name' => 'Jumlah Roda',
                    'type' => 'number',
                    'required' => false,
                ],

                [
                    'name' => 'Jenis Karoseri',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Bak',
                        'Box',
                        'Dump',
                        'Tangki',
                        'Flatbed',
                        'Wingbox',
                        'Container',
                        'Lainnya',
                    ],
                ],

                [
                    'name' => 'Status KIR',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        'Aktif',
                        'Mati',
                        'Tidak Ada',
                    ],
                ],

            ],

        ];

        DB::transaction(function () use ($category, $subcategories) {

            foreach ($subcategories as $subcategorySlug => $questions) {

                $subcategory = SubCategory::where('category_id', $category->id)
                    ->where('slug', $subcategorySlug)
                    ->firstOrFail();

                foreach ($questions as $index => $question) {

                    $slug = $subcategorySlug . '-' . Str::slug($question['name']);

                    $attribute = ProductAttribute::updateOrCreate(
                        [
                            'slug' => $slug,
                        ],
                        [
                            'name' => $question['name'],
                            'type' => $question['type'],
                            'filterable' => true,
                            'required' => false,
                            'searchable' => true,
                            'sort' => $index + 1,
                        ]
                    );

                    foreach (($question['options'] ?? []) as $optionIndex => $option) {

                        ProductAttributeOption::firstOrCreate(
                            [
                                'product_attribute_id' => $attribute->id,
                                'value' => $option,
                            ],
                            [
                                'sort' => $optionIndex + 1,
                            ]
                        );

                    }

                    SubCategoryAttribute::updateOrCreate(
                        [
                            'sub_category_id' => $subcategory->id,
                            'product_attribute_id' => $attribute->id,
                            'listing_type' => 'product',
                        ],
                        [
                            'required' => $question['required'] ?? false,
                            'filterable' => true,
                            'searchable' => true,
                            'visible' => true,
                            'sort' => $index + 10,
                        ]
                    );

                }

                $this->command?->info(
                    "Atribut barang subkategori {$subcategory->name} berhasil disimpan."
                );
            }

        });

    }
}