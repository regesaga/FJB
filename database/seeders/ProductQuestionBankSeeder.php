<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductQuestionBankSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [

            // ==========================================
            // PERTANYAAN UMUM
            // ==========================================
            [
                'name' => 'Merek',
                'slug' => 'merek-produk',
                'type' => 'text',
                'group' => 'umum',
                'options' => [],
            ],
            [
                'name' => 'Kondisi Barang',
                'slug' => 'kondisi-barang',
                'type' => 'select',
                'group' => 'umum',
                'options' => ['Baru', 'Bekas', 'Refurbished'],
            ],
            [
                'name' => 'Warna',
                'slug' => 'warna',
                'type' => 'select',
                'group' => 'umum',
                'options' => [
                    'Hitam', 'Putih', 'Merah', 'Biru',
                    'Hijau', 'Kuning', 'Abu-abu', 'Silver',
                    'Cokelat', 'Pink', 'Lainnya',
                ],
            ],
            [
                'name' => 'Ukuran',
                'slug' => 'ukuran-produk',
                'type' => 'text',
                'group' => 'umum',
                'options' => [],
            ],
            [
                'name' => 'Material',
                'slug' => 'material-produk',
                'type' => 'text',
                'group' => 'umum',
                'options' => [],
            ],
            [
                'name' => 'Tahun Pembelian',
                'slug' => 'tahun-pembelian',
                'type' => 'number',
                'group' => 'umum',
                'options' => [],
            ],
            [
                'name' => 'Garansi',
                'slug' => 'garansi-produk',
                'type' => 'select',
                'group' => 'umum',
                'options' => [
                    'Garansi resmi',
                    'Garansi toko',
                    'Garansi personal',
                    'Tidak ada garansi',
                ],
            ],
            [
                'name' => 'Kelengkapan Produk',
                'slug' => 'kelengkapan-produk',
                'type' => 'checkbox',
                'group' => 'umum',
                'options' => [
                    'Kardus',
                    'Buku manual',
                    'Kabel',
                    'Charger',
                    'Aksesori',
                    'Nota pembelian',
                ],
            ],

            // ==========================================
            // MOTOR
            // ==========================================
            [
                'name' => 'Tahun Kendaraan',
                'slug' => 'tahun-kendaraan',
                'type' => 'number',
                'group' => 'motor',
                'options' => [],
            ],
            [
                'name' => 'Merek Motor',
                'slug' => 'merek-motor',
                'type' => 'select',
                'group' => 'motor',
                'options' => [
                    'Honda', 'Yamaha', 'Suzuki',
                    'Kawasaki', 'TVS', 'Vespa', 'KTM', 'Lainnya',
                ],
            ],
            [
                'name' => 'Tipe Motor',
                'slug' => 'tipe-motor',
                'type' => 'text',
                'group' => 'motor',
                'options' => [],
            ],
            [
                'name' => 'Jenis Motor',
                'slug' => 'motor-jenis-motor',
                'type' => 'select',
                'group' => 'motor',
                'options' => [
                    'Matic', 'Bebek', 'Sport',
                    'Trail', 'Cub', 'Listrik', 'Lainnya',
                ],
            ],
            [
                'name' => 'Kapasitas Mesin (CC)',
                'slug' => 'kapasitas-mesin-cc',
                'type' => 'number',
                'group' => 'motor',
                'options' => [],
            ],
            [
                'name' => 'Transmisi',
                'slug' => 'transmisi',
                'type' => 'select',
                'group' => 'motor',
                'options' => ['Manual', 'Otomatis', 'Semi Otomatis'],
            ],
            [
                'name' => 'Jarak Tempuh (KM)',
                'slug' => 'jarak-tempuh-km',
                'type' => 'number',
                'group' => 'motor',
                'options' => [],
            ],
            [
                'name' => 'Status Pajak',
                'slug' => 'motor-status-pajak',
                'type' => 'select',
                'group' => 'motor',
                'options' => ['Aktif', 'Mati'],
            ],

            // ==========================================
            // MOBIL
            // ==========================================
            [
                'name' => 'Merek Mobil',
                'slug' => 'merek-mobil',
                'type' => 'select',
                'group' => 'mobil',
                'options' => [
                    'Toyota', 'Honda', 'Mitsubishi',
                    'Suzuki', 'Daihatsu', 'Nissan',
                    'Mazda', 'Hyundai', 'Wuling', 'Lainnya',
                ],
            ],
            [
                'name' => 'Tipe Mobil',
                'slug' => 'tipe-mobil',
                'type' => 'text',
                'group' => 'mobil',
                'options' => [],
            ],
            [
                'name' => 'Jenis Bahan Bakar',
                'slug' => 'jenis-bahan-bakar',
                'type' => 'select',
                'group' => 'mobil',
                'options' => ['Bensin', 'Diesel', 'Hybrid', 'Listrik'],
            ],
            [
                'name' => 'Jumlah Penumpang',
                'slug' => 'jumlah-penumpang',
                'type' => 'number',
                'group' => 'mobil',
                'options' => [],
            ],

            // ==========================================
            // PROPERTI
            // ==========================================
            [
                'name' => 'Luas Tanah (m²)',
                'slug' => 'luas-tanah',
                'type' => 'number',
                'group' => 'properti',
                'options' => [],
            ],
            [
                'name' => 'Luas Bangunan (m²)',
                'slug' => 'luas-bangunan',
                'type' => 'number',
                'group' => 'properti',
                'options' => [],
            ],
            [
                'name' => 'Jumlah Kamar Tidur',
                'slug' => 'jumlah-kamar-tidur',
                'type' => 'number',
                'group' => 'properti',
                'options' => [],
            ],
            [
                'name' => 'Jumlah Kamar Mandi',
                'slug' => 'jumlah-kamar-mandi',
                'type' => 'number',
                'group' => 'properti',
                'options' => [],
            ],
            [
                'name' => 'Jenis Sertifikat',
                'slug' => 'jenis-sertifikat',
                'type' => 'select',
                'group' => 'properti',
                'options' => [
                    'SHM', 'SHGB', 'SHSRS',
                    'AJB', 'Girik', 'Lainnya',
                ],
            ],
            [
                'name' => 'Akses Jalan',
                'slug' => 'akses-jalan-properti',
                'type' => 'select',
                'group' => 'properti',
                'options' => [
                    'Mobil', 'Motor', 'Jalan setapak', 'Belum ada akses',
                ],
            ],

            // ==========================================
            // ELEKTRONIK
            // ==========================================
            [
                'name' => 'Model Produk',
                'slug' => 'model-produk',
                'type' => 'text',
                'group' => 'elektronik',
                'options' => [],
            ],
            [
                'name' => 'Kapasitas',
                'slug' => 'kapasitas-elektronik',
                'type' => 'text',
                'group' => 'elektronik',
                'options' => [],
            ],
            [
                'name' => 'Konsumsi Daya (Watt)',
                'slug' => 'konsumsi-daya-watt',
                'type' => 'number',
                'group' => 'elektronik',
                'options' => [],
            ],

            // ==========================================
            // HANDPHONE DAN KOMPUTER
            // ==========================================
            [
                'name' => 'RAM (GB)',
                'slug' => 'ram-gb',
                'type' => 'number',
                'group' => 'handphone',
                'options' => [],
            ],
            [
                'name' => 'Memori Internal (GB)',
                'slug' => 'memori-internal-gb',
                'type' => 'number',
                'group' => 'handphone',
                'options' => [],
            ],
            [
                'name' => 'Kapasitas Baterai (mAh)',
                'slug' => 'kapasitas-baterai-mah',
                'type' => 'number',
                'group' => 'handphone',
                'options' => [],
            ],
            [
                'name' => 'Kesehatan Baterai (%)',
                'slug' => 'kesehatan-baterai',
                'type' => 'number',
                'group' => 'handphone',
                'options' => [],
            ],
            [
                'name' => 'Processor',
                'slug' => 'processor',
                'type' => 'text',
                'group' => 'komputer',
                'options' => [],
            ],
            [
                'name' => 'Jenis Penyimpanan',
                'slug' => 'jenis-penyimpanan',
                'type' => 'select',
                'group' => 'komputer',
                'options' => ['HDD', 'SSD SATA', 'SSD NVMe', 'eMMC'],
            ],
            [
                'name' => 'Kapasitas Penyimpanan (GB)',
                'slug' => 'kapasitas-penyimpanan-gb',
                'type' => 'number',
                'group' => 'komputer',
                'options' => [],
            ],

            // ==========================================
            // FASHION
            // ==========================================
            [
                'name' => 'Ukuran Pakaian',
                'slug' => 'ukuran-pakaian',
                'type' => 'select',
                'group' => 'fashion',
                'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'],
            ],
            [
                'name' => 'Jenis Kelamin Pengguna',
                'slug' => 'jenis-kelamin-pengguna',
                'type' => 'select',
                'group' => 'fashion',
                'options' => ['Pria', 'Wanita', 'Unisex', 'Anak-anak'],
            ],

            // ==========================================
            // FURNITURE
            // ==========================================
            [
                'name' => 'Panjang (cm)',
                'slug' => 'panjang-cm',
                'type' => 'number',
                'group' => 'furniture',
                'options' => [],
            ],
            [
                'name' => 'Lebar (cm)',
                'slug' => 'lebar-cm',
                'type' => 'number',
                'group' => 'furniture',
                'options' => [],
            ],
            [
                'name' => 'Tinggi (cm)',
                'slug' => 'tinggi-cm',
                'type' => 'number',
                'group' => 'furniture',
                'options' => [],
            ],

            // ==========================================
            // PERTANIAN
            // ==========================================
            [
                'name' => 'Jenis Tanaman',
                'slug' => 'jenis-tanaman',
                'type' => 'text',
                'group' => 'pertanian',
                'options' => [],
            ],
            [
                'name' => 'Varietas',
                'slug' => 'varietas-tanaman',
                'type' => 'text',
                'group' => 'pertanian',
                'options' => [],
            ],
            [
                'name' => 'Masa Panen (Hari)',
                'slug' => 'masa-panen-hari',
                'type' => 'number',
                'group' => 'pertanian',
                'options' => [],
            ],

            // ==========================================
            // HEWAN
            // ==========================================
            [
                'name' => 'Jenis Hewan',
                'slug' => 'jenis-hewan',
                'type' => 'text',
                'group' => 'hewan',
                'options' => [],
            ],
            [
                'name' => 'Ras',
                'slug' => 'ras-hewan',
                'type' => 'text',
                'group' => 'hewan',
                'options' => [],
            ],
            [
                'name' => 'Usia Hewan (Bulan)',
                'slug' => 'usia-hewan-bulan',
                'type' => 'number',
                'group' => 'hewan',
                'options' => [],
            ],
            [
                'name' => 'Jenis Kelamin Hewan',
                'slug' => 'jenis-kelamin-hewan',
                'type' => 'select',
                'group' => 'hewan',
                'options' => ['Jantan', 'Betina', 'Belum diketahui'],
            ],
        ];

        DB::transaction(function () use ($questions) {
            foreach ($questions as $index => $data) {
                $attribute = ProductAttribute::firstOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'required' => false,
                        'filterable' => false,
                        'searchable' => true,
                        'sort' => $index + 1,
                    ]
                );

                // Hanya memperbarui metadata rekomendasi.
                // Konfigurasi pertanyaan lama tidak ditimpa.
                $attribute->update([
                    'is_recommended' => true,
                    'recommendation_group' => $data['group'],
                ]);

                // Tambahkan opsi jika belum tersedia.
                foreach ($data['options'] as $optionIndex => $option) {
                    ProductAttributeOption::firstOrCreate(
                        [
                            'product_attribute_id' => $attribute->id,
                            'value' => $option,
                        ],
                        [
                            'sort' => $optionIndex + 1,
                        ]
                    );
                }
            }
        });

        $this->command->info(
            count($questions) . ' rekomendasi pertanyaan produk berhasil disinkronkan.'
        );
    }
}