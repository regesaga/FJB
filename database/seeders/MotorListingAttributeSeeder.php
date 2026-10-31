<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotorListingAttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $motor = SubCategory::where('slug', 'motor')->firstOrFail();

            $attributes = [
                [
                    'name' => 'Tahun Kendaraan',
                    'slug' => 'tahun-kendaraan',
                    'type' => 'number',
                    'required' => true,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 1,
                    'options' => [],
                ],
                [
                    'name' => 'Merek Motor',
                    'slug' => 'merek-motor',
                    'type' => 'select',
                    'required' => true,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 2,
                    'options' => [
                        'Honda', 'Yamaha', 'Suzuki', 'Kawasaki',
                        'TVS', 'Vespa', 'KTM', 'Lainnya',
                    ],
                ],
                [
                    'name' => 'Tipe Motor',
                    'slug' => 'tipe-motor',
                    'type' => 'text',
                    'required' => true,
                    'filterable' => false,
                    'searchable' => true,
                    'sort' => 3,
                    'options' => [],
                ],
                [
                    'name' => 'Jenis Motor',
                    'slug' => 'motor-jenis-motor',
                    'type' => 'select',
                    'required' => true,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 4,
                    'options' => [
                        'Matic', 'Bebek', 'Sport', 'Trail',
                        'Cub', 'Listrik', 'Lainnya',
                    ],
                ],
                [
                    'name' => 'Kapasitas Mesin (CC)',
                    'slug' => 'kapasitas-mesin-cc',
                    'type' => 'number',
                    'required' => false,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 5,
                    'options' => [],
                ],
                [
                    'name' => 'Transmisi',
                    'slug' => 'transmisi',
                    'type' => 'select',
                    'required' => true,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 6,
                    'options' => [
                        'Manual', 'Otomatis', 'Semi Otomatis',
                    ],
                ],
                [
                    'name' => 'Kondisi Kendaraan',
                    'slug' => 'kondisi-kendaraan',
                    'type' => 'select',
                    'required' => true,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 7,
                    'options' => ['Baru', 'Bekas'],
                ],
                [
                    'name' => 'Jarak Tempuh (KM)',
                    'slug' => 'jarak-tempuh-km',
                    'type' => 'number',
                    'required' => false,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 8,
                    'options' => [],
                ],
                [
                    'name' => 'Warna',
                    'slug' => 'warna',
                    'type' => 'select',
                    'required' => false,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 9,
                    'options' => [
                        'Hitam', 'Putih', 'Merah', 'Biru',
                        'Abu-abu', 'Silver', 'Hijau', 'Kuning', 'Lainnya',
                    ],
                ],
                [
                    'name' => 'Status Pajak',
                    'slug' => 'motor-status-pajak',
                    'type' => 'select',
                    'required' => false,
                    'filterable' => true,
                    'searchable' => true,
                    'sort' => 10,
                    'options' => ['Aktif', 'Mati'],
                ],
                [
                    'name' => 'Kelengkapan Surat',
                    'slug' => 'motor-kelengkapan-surat',
                    'type' => 'checkbox',
                    'required' => false,
                    'filterable' => false,
                    'searchable' => false,
                    'sort' => 11,
                    'options' => ['STNK', 'BPKB'],
                ],
                [
                    'name' => 'Sistem Starter',
                    'slug' => 'motor-sistem-starter',
                    'type' => 'select',
                    'required' => false,
                    'filterable' => false,
                    'searchable' => false,
                    'sort' => 12,
                    'options' => [
                        'Elektrik',
                        'Kick Starter',
                        'Elektrik dan Kick Starter',
                    ],
                ],
            ];

            foreach ($attributes as $data) {
                $attribute = ProductAttribute::updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'required' => $data['required'],
                        'filterable' => $data['filterable'],
                        'searchable' => $data['searchable'],
                        'sort' => $data['sort'],
                    ]
                );

                foreach ($data['options'] as $index => $option) {
                    ProductAttributeOption::updateOrCreate(
                        [
                            'product_attribute_id' => $attribute->id,
                            'value' => $option,
                        ],
                        [
                            'sort' => $index + 1,
                        ]
                    );
                }

                SubCategoryAttribute::updateOrCreate(
                    [
                        'sub_category_id' => $motor->id,
                        'product_attribute_id' => $attribute->id,
                        'listing_type' => 'product',
                    ],
                    [
                        'required' => $data['required'],
                        'filterable' => $data['filterable'],
                        'searchable' => $data['searchable'],
                        'visible' => true,
                        'sort' => $data['sort'],
                    ]
                );
            }

            $this->command->info('12 atribut Motor berhasil disinkronkan.');
        });
    }
}