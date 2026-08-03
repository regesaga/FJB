<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Database\Seeder;

class RegencySeeder extends Seeder
{
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(database_path('data/wilayah/regencies.json')),
            true
        );

        foreach ($json as $item) {

            $province = Province::where('code', $item['province_code'])->first();

            if (!$province) {
                continue;
            }

            Regency::updateOrCreate(
                [
                    'code' => $item['id']
                ],
                [
                    'province_code' => $province->code,
                    'name' => $item['name']
                ]
            );
        }
    }
}