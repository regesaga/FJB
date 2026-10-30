<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class ProductAttributeOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            'transmisi' => [
                'Manual',
                'Automatic',
                'CVT',
            ],

            'bahan-bakar' => [
                'Bensin',
                'Diesel',
                'Listrik',
                'Hybrid',
            ],

            'sertifikat' => [
                'SHM',
                'SHGB',
                'SHGU',
                'AJB',
                'Girik',
                'Lainnya',
            ],

            'kondisi' => [
                'Baru',
                'Bekas',
            ],

            'garansi' => [
                'Garansi Resmi',
                'Garansi Toko',
                'Garansi Personal',
                'Tidak Ada Garansi',
            ],
        ];

        foreach ($options as $slug => $values) {
            $attribute = ProductAttribute::where('slug', $slug)->first();

            if (!$attribute) {
                continue;
            }

            foreach ($values as $index => $value) {
                $attribute->options()->firstOrCreate(
                    ['value' => $value],
                    ['sort' => $index + 1]
                );
            }
        }
    }
}