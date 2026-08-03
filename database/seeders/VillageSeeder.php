<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(database_path('data/wilayah/villages.json')),
            true
        );

        foreach ($json as $item) {

            $district = District::where(
                'code',
                $item['district_code']
            )->first();

            if (!$district) {
                continue;
            }

            Village::updateOrCreate(
                [
                    'code' => $item['id']
                ],
                [
                    'district_code' => $district->code,
                    'name' => $item['name'],
                    'postal_code' => $item['postal_code'] ?? null,
                ]
            );
        }
    }
}