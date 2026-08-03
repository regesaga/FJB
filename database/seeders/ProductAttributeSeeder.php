<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [

            ['Merek','text'],
            ['Model','text'],
            ['Tahun','number'],
            ['Warna','text'],
            ['Kilometer','number'],
            ['Transmisi','select'],
            ['Bahan Bakar','select'],
            ['Luas Tanah','number'],
            ['Luas Bangunan','number'],
            ['Sertifikat','select'],
            ['Kamar Tidur','number'],
            ['Kamar Mandi','number'],
            ['RAM','number'],
            ['ROM','number'],
            ['Chipset','text'],
            ['Kondisi','select'],
            ['Garansi','select']

        ];

        foreach($attributes as $i=>$attribute){

            ProductAttribute::updateOrCreate(

                ['slug'=>Str::slug($attribute[0])],

                [

                    'name'=>$attribute[0],

                    'slug'=>Str::slug($attribute[0]),

                    'type'=>$attribute[1],

                    'filterable'=>true,

                    'required'=>false,

                    'searchable'=>true,

                    'sort'=>$i+1

                ]

            );

        }

    }
}