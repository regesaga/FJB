<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,

            CategorySeeder::class,
            SubCategorySeeder::class,

            ProductAttributeSeeder::class,
            ProductAttributeOptionSeeder::class,
            CategoryAttributeSeeder::class,

            // Atribut dan pertanyaan khusus jasa
            ServiceAttributeSeeder::class,

            ProvinceSeeder::class,
            RegencySeeder::class,
            DistrictSeeder::class,
            VillageSeeder::class,
        ]);
    }
}