<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // 1. Kendaraan
            'Kendaraan' => [
                'Motor',
                'Mobil',
                'Truk',
                'Bus',
                'Sepeda',
                'Sparepart',
                'Aksesoris',
            ],

            // 2. Properti
            'Properti' => [
                'Rumah',
                'Tanah',
                'Ruko',
                'Apartemen',
                'Kos',
                'Gudang',
            ],

            // 3. Bangunan & Material
            'Bangunan & Material' => [
                'Semen',
                'Pasir',
                'Batu',
                'Bata dan Batako',
                'Besi dan Baja',
                'Baja Ringan',
                'Kayu',
                'Keramik dan Granit',
                'Pintu dan Jendela',
                'Atap dan Genteng',
                'Cat dan Pelapis',
                'Pipa dan Instalasi Air',
                'Peralatan Bangunan',
                'Material Bangunan Lainnya',
            ],

            // 4. Elektronik
            'Elektronik' => [
                'HP',
                'Laptop',
                'Komputer',
                'TV',
                'Kamera',
            ],

            // 5. Fashion
            'Fashion' => [
                'Pria',
                'Wanita',
                'Anak',
            ],

            // 6. UMKM & Produk Lokal
            'UMKM & Produk Lokal' => [
                'Makanan',
                'Minuman',
                'Kue dan Roti',
                'Oleh-oleh Khas Daerah',
                'Kerajinan Tangan',
                'Produk Rumahan',
                'Produk Olahan',
                'Fashion Lokal',
                'Produk UMKM Lainnya',
            ],

            // 7. Pertanian
            'Pertanian' => [
                'Alat Pertanian',
                'Bibit',
                'Pupuk',
                'Hasil Panen',
            ],

            // 8. Peternakan
            'Peternakan' => [
                'Sapi',
                'Kambing',
                'Domba',
                'Ayam',
                'Bebek',
                'Burung Ternak',
                'Ikan Konsumsi',
                'Bibit Ternak',
                'Pakan Ternak',
                'Peralatan Peternakan',
                'Hasil Peternakan',
                'Lainnya',
            ],

            // 9. Hewan Peliharaan
            'Hewan Peliharaan' => [
                'Kucing',
                'Anjing',
                'Burung',
                'Ikan',
            ],

            // 10. Jasa
            'Jasa' => [
                'Servis',
                'Bangunan',
                'Transportasi',
                'Pendidikan',
            ],

            // 11. Hobi & Olahraga
            'Hobi & Olahraga' => [
                'Sepeda',
                'Alat Musik',
                'Gaming',
                'Olahraga',
            ],

            // 12. Lowongan Kerja
            'Lowongan Kerja' => [
                'Full Time',
                'Part Time',
                'Freelance',
            ],

            // 13. Lainnya
            'Lainnya' => [
                'Lain-lain',
            ],
        ];

        foreach ($data as $categoryName => $subCategories) {

            // Cari kategori berdasarkan nama
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                $this->command?->warn(
                    "Kategori tidak ditemukan: {$categoryName}"
                );

                continue;
            }

            foreach ($subCategories as $subCategoryName) {

                $slug = Str::slug($subCategoryName);

                // Buat atau perbarui subkategori
                SubCategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => $slug,
                    ],
                    [
                        'name' => $subCategoryName,
                    ]
                );
            }

            $this->command?->info(
                "Subkategori {$categoryName} berhasil diproses."
            );
        }

        $this->command?->info(
            'Seluruh proses SubCategorySeeder selesai.'
        );
    }
}