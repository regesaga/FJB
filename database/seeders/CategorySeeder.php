<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Kendaraan',
            'Properti',
            'Elektronik',
            'Fashion',
            'Rumah Tangga',
            'Hobi',
            'Olahraga',
            'Pertanian',
            'Peternakan',
            'Jasa',
            'Lowongan Kerja',
            'UMKM',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'is_active' => true,
                ]
            );
        }
    }
}