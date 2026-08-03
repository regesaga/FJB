<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kendaraan',
                'icon' => 'heroicon-o-truck',
            ],
            [
                'name' => 'Properti',
                'icon' => 'heroicon-o-home',
            ],
            [
                'name' => 'Elektronik',
                'icon' => 'heroicon-o-device-phone-mobile',
            ],
            [
                'name' => 'Fashion',
                'icon' => 'heroicon-o-shopping-bag',
            ],
            [
                'name' => 'Jasa',
                'icon' => 'heroicon-o-wrench-screwdriver',
            ],
            [
                'name' => 'Pertanian',
                'icon' => 'heroicon-o-sparkles',
            ],
            [
                'name' => 'Hobi & Olahraga',
                'icon' => 'heroicon-o-trophy',
            ],
            [
                'name' => 'Hewan Peliharaan',
                'icon' => 'heroicon-o-heart',
            ],
            [
                'name' => 'Lowongan Kerja',
                'icon' => 'heroicon-o-briefcase',
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'heroicon-o-squares-2x2',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'icon' => $category['icon'],
                    'image' => null,
                    'sort' => 0,
                    'is_active' => true,
                ]
            );
        }
    }
}