<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(database_path('data/wilayah/provinces.json')),
            true
        );

        foreach ($json as $item) {

            Province::updateOrCreate(
                ['code' => $item['id']],
                [
                    'name' => $item['name']
                ]
            );
        }
    }
}