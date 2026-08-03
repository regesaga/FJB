<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            RoleSeeder::class,
    
            AdminSeeder::class,
    
            CategorySeeder::class,
    
            SubCategorySeeder::class,
    
            ProductAttributeSeeder::class,
            ProvinceSeeder::class,
            RegencySeeder::class,
            DistrictSeeder::class,
            VillageSeeder::class,
    
        ]);
    }
}