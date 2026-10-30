<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class CategoryAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Kendaraan' => [
                'merek',
                'model',
                'tahun',
                'warna',
                'kilometer',
                'transmisi',
                'bahan-bakar',
                'kondisi',
                'garansi',
            ],

            'Properti' => [
                'luas-tanah',
                'luas-bangunan',
                'sertifikat',
                'kamar-tidur',
                'kamar-mandi',
                'kondisi',
            ],

            'Elektronik' => [
                'merek',
                'model',
                'tahun',
                'warna',
                'ram',
                'rom',
                'chipset',
                'kondisi',
                'garansi',
            ],

            'Fashion' => [
                'merek',
                'warna',
                'kondisi',
            ],

            'Hobi & Olahraga' => [
                'merek',
                'model',
                'kondisi',
            ],

            'Pertanian' => [
                'merek',
                'kondisi',
            ],
        ];

        foreach ($mapping as $categoryName => $attributeSlugs) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            foreach ($attributeSlugs as $index => $slug) {
                $attribute = ProductAttribute::where('slug', $slug)->first();

                if (!$attribute) {
                    continue;
                }

                \DB::table('category_attributes')->updateOrInsert(
                    [
                        'category_id' => $category->id,
                        'product_attribute_id' => $attribute->id,
                    ],
                    [
                        'required' => false,
                        'filterable' => true,
                        'searchable' => true,
                        'visible' => true,
                        'sort' => $index + 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}