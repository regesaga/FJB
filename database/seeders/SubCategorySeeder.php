<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            'Kendaraan' => [
                'Motor',
                'Mobil',
                'Truk',
                'Bus',
                'Sepeda',
                'Sparepart',
                'Aksesoris'
            ],

            'Properti' => [
                'Rumah',
                'Tanah',
                'Ruko',
                'Apartemen',
                'Kos',
                'Gudang'
            ],

            'Elektronik' => [
                'HP',
                'Laptop',
                'Komputer',
                'TV',
                'Kamera'
            ],

            'Fashion' => [
                'Pria',
                'Wanita',
                'Anak'
            ],

            'Jasa' => [
                'Servis',
                'Bangunan',
                'Transportasi',
                'Pendidikan'
            ],

            'Pertanian' => [
                'Alat Pertanian',
                'Bibit',
                'Pupuk',
                'Hasil Panen'
            ],

            'Hobi & Olahraga' => [
                'Sepeda',
                'Alat Musik',
                'Gaming',
                'Olahraga'
            ],

            'Hewan Peliharaan' => [
                'Kucing',
                'Anjing',
                'Burung',
                'Ikan'
            ],

            'Lowongan Kerja' => [
                'Full Time',
                'Part Time',
                'Freelance'
            ],

            'Lainnya' => [
                'Lain-lain'
            ]

        ];

        foreach ($data as $categoryName => $subs) {

            $category = Category::where('name', $categoryName)->first();

            if (!$category) continue;

            foreach ($subs as $index => $sub) {

                SubCategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($sub)
                    ],
                    [
                        'name' => $sub,
                        'sort' => $index + 1,
                        'is_active' => true
                    ]
                );
            }

        }
    }
}