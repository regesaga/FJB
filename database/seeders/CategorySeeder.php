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
                'name' => 'Bangunan & Material',
                'icon' => 'heroicon-o-building-office-2',
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
                'name' => 'UMKM & Produk Lokal',
                'icon' => 'heroicon-o-building-storefront',
            ],
            [
                'name' => 'Pertanian',
                'icon' => 'heroicon-o-sparkles',
            ],
            [
                'name' => 'Peternakan',
                'icon' => 'heroicon-o-beaker',
            ],
            [
                'name' => 'Hewan Peliharaan',
                'icon' => 'heroicon-o-heart',
            ],
            [
                'name' => 'Jasa',
                'icon' => 'heroicon-o-wrench-screwdriver',
            ],
            [
                'name' => 'Hobi & Olahraga',
                'icon' => 'heroicon-o-trophy',
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

        foreach ($categories as $index => $category) {
            $slug = Str::slug($category['name']);

            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'image' => null,
                    'sort' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}