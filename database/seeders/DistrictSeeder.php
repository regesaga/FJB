<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Regency;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(database_path('data/wilayah/districts.json')),
            true
        );

        foreach ($json as $item) {

            $regency = Regency::where('code', $item['regency_code'])->first();

            if (!$regency) {
                continue;
            }
            
            District::updateOrCreate(
                [
                    'code' => $item['id']
                ],
                [
                    'regency_code' => $regency->code,
                    'name' => $item['name']
                ]
            );
        }
    }
}