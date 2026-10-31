<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceQuestionBankSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'umum' => [
                [
                    'name' => 'Jenis Layanan',
                    'slug' => 'bank-jasa-jenis-layanan',
                    'type' => 'text',
                ],
                [
                    'name' => 'Pengalaman Kerja (Tahun)',
                    'slug' => 'bank-jasa-pengalaman-kerja',
                    'type' => 'number',
                ],
                [
                    'name' => 'Area Layanan',
                    'slug' => 'bank-jasa-area-layanan',
                    'type' => 'checkbox',
                    'options' => ['Dalam Kecamatan', 'Dalam Kabupaten', 'Antar Kabupaten', 'Seluruh Indonesia'],
                ],
                [
                    'name' => 'Sistem Pengerjaan',
                    'slug' => 'bank-jasa-sistem-pengerjaan',
                    'type' => 'select',
                    'options' => ['Harian', 'Per Jam', 'Borongan', 'Per Proyek', 'Bulanan', 'Kontrak'],
                ],
                [
                    'name' => 'Menerima Panggilan',
                    'slug' => 'bank-jasa-menerima-panggilan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
                [
                    'name' => 'Jadwal Operasional',
                    'slug' => 'bank-jasa-jadwal-operasional',
                    'type' => 'text',
                ],
                [
                    'name' => 'Deskripsi Layanan',
                    'slug' => 'bank-jasa-deskripsi-layanan',
                    'type' => 'textarea',
                ],
            ],

            'konstruksi' => [
                [
                    'name' => 'Jenis Pekerjaan Bangunan',
                    'slug' => 'bank-jasa-jenis-pekerjaan-bangunan',
                    'type' => 'checkbox',
                    'options' => ['Bangun Rumah', 'Renovasi', 'Pengecatan', 'Pemasangan Keramik', 'Plafon', 'Pagar', 'Instalasi Listrik', 'Instalasi Air', 'Lainnya'],
                ],
                [
                    'name' => 'Skala Pengerjaan',
                    'slug' => 'bank-jasa-skala-pengerjaan',
                    'type' => 'select',
                    'options' => ['Kecil', 'Menengah', 'Besar'],
                ],
                [
                    'name' => 'Penyediaan Material',
                    'slug' => 'bank-jasa-penyediaan-material',
                    'type' => 'radio',
                    'options' => ['Penyedia Jasa', 'Pemilik Proyek', 'Bisa Keduanya'],
                ],
                [
                    'name' => 'Luas Pengerjaan (m²)',
                    'slug' => 'bank-jasa-luas-pengerjaan',
                    'type' => 'number',
                ],
            ],

            'otomotif' => [
                [
                    'name' => 'Jenis Kendaraan yang Dilayani',
                    'slug' => 'bank-jasa-jenis-kendaraan',
                    'type' => 'checkbox',
                    'options' => ['Sepeda Motor', 'Mobil', 'Pickup', 'Truk', 'Bus', 'Lainnya'],
                ],
                [
                    'name' => 'Merek Kendaraan yang Dilayani',
                    'slug' => 'bank-jasa-merek-kendaraan',
                    'type' => 'text',
                ],
                [
                    'name' => 'Jenis Perbaikan',
                    'slug' => 'bank-jasa-jenis-perbaikan',
                    'type' => 'checkbox',
                    'options' => ['Servis Rutin', 'Perbaikan Mesin', 'Kelistrikan', 'Kaki-kaki', 'Body Repair', 'Ganti Oli', 'Lainnya'],
                ],
                [
                    'name' => 'Menerima Servis Panggilan',
                    'slug' => 'bank-jasa-servis-panggilan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'kebersihan' => [
                [
                    'name' => 'Jenis Layanan Kebersihan',
                    'slug' => 'bank-jasa-jenis-kebersihan',
                    'type' => 'checkbox',
                    'options' => ['Rumah', 'Kantor', 'Gedung', 'Sofa', 'Kasur', 'Karpet', 'Pasca Renovasi'],
                ],
                [
                    'name' => 'Jenis Lokasi yang Dilayani',
                    'slug' => 'bank-jasa-lokasi-kebersihan',
                    'type' => 'checkbox',
                    'options' => ['Rumah', 'Kantor', 'Toko', 'Hotel', 'Pabrik', 'Tempat Umum'],
                ],
                [
                    'name' => 'Menyediakan Peralatan',
                    'slug' => 'bank-jasa-menyediakan-peralatan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'transportasi' => [
                [
                    'name' => 'Jenis Transportasi',
                    'slug' => 'bank-jasa-jenis-transportasi',
                    'type' => 'checkbox',
                    'options' => ['Antar Jemput', 'Carter', 'Wisata', 'Angkutan Barang', 'Pindahan'],
                ],
                [
                    'name' => 'Kapasitas Penumpang',
                    'slug' => 'bank-jasa-kapasitas-penumpang',
                    'type' => 'number',
                ],
                [
                    'name' => 'Area Tujuan Layanan',
                    'slug' => 'bank-jasa-area-tujuan',
                    'type' => 'checkbox',
                    'options' => ['Dalam Kota', 'Dalam Kabupaten', 'Antar Kota', 'Antar Provinsi'],
                ],
                [
                    'name' => 'Menerima Pesanan Luar Kota',
                    'slug' => 'bank-jasa-pesanan-luar-kota',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'pendidikan' => [
                [
                    'name' => 'Bidang atau Mata Pelajaran',
                    'slug' => 'bank-jasa-bidang-pelajaran',
                    'type' => 'text',
                ],
                [
                    'name' => 'Jenjang Pendidikan',
                    'slug' => 'bank-jasa-jenjang-pendidikan',
                    'type' => 'checkbox',
                    'options' => ['PAUD / TK', 'SD / MI', 'SMP / MTs', 'SMA / SMK / MA', 'Perguruan Tinggi', 'Umum'],
                ],
                [
                    'name' => 'Metode Pembelajaran',
                    'slug' => 'bank-jasa-metode-pembelajaran',
                    'type' => 'select',
                    'options' => ['Tatap Muka', 'Online', 'Keduanya'],
                ],
            ],

            'teknologi' => [
                [
                    'name' => 'Jenis Layanan Teknologi',
                    'slug' => 'bank-jasa-jenis-teknologi',
                    'type' => 'checkbox',
                    'options' => ['Pembuatan Website', 'Aplikasi Mobile', 'Instalasi Jaringan', 'Perbaikan Komputer', 'Konsultasi IT', 'Digital Marketing', 'SEO'],
                ],
                [
                    'name' => 'Teknologi yang Digunakan',
                    'slug' => 'bank-jasa-teknologi-digunakan',
                    'type' => 'text',
                ],
                [
                    'name' => 'Model Pengerjaan',
                    'slug' => 'bank-jasa-model-pengerjaan',
                    'type' => 'select',
                    'options' => ['Per Proyek', 'Per Jam', 'Bulanan', 'Kontrak'],
                ],
                [
                    'name' => 'Bisa Dikerjakan Online',
                    'slug' => 'bank-jasa-dikerjakan-online',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'desain' => [
                [
                    'name' => 'Jenis Desain',
                    'slug' => 'bank-jasa-jenis-desain',
                    'type' => 'checkbox',
                    'options' => ['Logo', 'Poster', 'Brosur', 'Banner', 'Kemasan Produk', 'Desain Interior', 'Desain Media Sosial'],
                ],
                [
                    'name' => 'Format Hasil Desain',
                    'slug' => 'bank-jasa-format-desain',
                    'type' => 'checkbox',
                    'options' => ['JPG', 'PNG', 'PDF', 'PSD', 'AI', 'CDR', 'Lainnya'],
                ],
                [
                    'name' => 'Jumlah Revisi',
                    'slug' => 'bank-jasa-jumlah-revisi',
                    'type' => 'select',
                    'options' => ['1 Kali', '2 Kali', '3 Kali', 'Tidak Terbatas'],
                ],
            ],

            'fotografi' => [
                [
                    'name' => 'Jenis Dokumentasi',
                    'slug' => 'bank-jasa-jenis-dokumentasi',
                    'type' => 'checkbox',
                    'options' => ['Pernikahan', 'Prewedding', 'Wisuda', 'Produk', 'Acara', 'Company Profile', 'Drone'],
                ],
                [
                    'name' => 'Durasi Layanan (Jam)',
                    'slug' => 'bank-jasa-durasi-dokumentasi',
                    'type' => 'number',
                ],
                [
                    'name' => 'Jenis Hasil Dokumentasi',
                    'slug' => 'bank-jasa-hasil-dokumentasi',
                    'type' => 'checkbox',
                    'options' => ['File Digital', 'Album Foto', 'Video', 'Video Editing', 'Cetak Foto'],
                ],
            ],

            'kecantikan' => [
                [
                    'name' => 'Jenis Layanan Kecantikan',
                    'slug' => 'bank-jasa-jenis-kecantikan',
                    'type' => 'checkbox',
                    'options' => ['Makeup Pengantin', 'Makeup Wisuda', 'Makeup Pesta', 'Hairdo', 'Perawatan Wajah', 'Perawatan Rambut'],
                ],
                [
                    'name' => 'Menerima Layanan Panggilan',
                    'slug' => 'bank-jasa-kecantikan-panggilan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
                [
                    'name' => 'Menyediakan Paket Layanan',
                    'slug' => 'bank-jasa-paket-kecantikan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'katering' => [
                [
                    'name' => 'Jenis Katering',
                    'slug' => 'bank-jasa-jenis-katering',
                    'type' => 'checkbox',
                    'options' => ['Harian', 'Pernikahan', 'Rapat', 'Nasi Kotak', 'Prasmanan', 'Snack Box'],
                ],
                [
                    'name' => 'Kapasitas Pesanan (Porsi)',
                    'slug' => 'bank-jasa-kapasitas-katering',
                    'type' => 'number',
                ],
                [
                    'name' => 'Menyediakan Layanan Prasmanan',
                    'slug' => 'bank-jasa-prasmanan',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'event' => [
                [
                    'name' => 'Jenis Acara',
                    'slug' => 'bank-jasa-jenis-acara',
                    'type' => 'checkbox',
                    'options' => ['Pernikahan', 'Khitanan', 'Ulang Tahun', 'Seminar', 'Gathering', 'Pentas Seni', 'Acara Pemerintahan'],
                ],
                [
                    'name' => 'Jenis Layanan Acara',
                    'slug' => 'bank-jasa-layanan-acara',
                    'type' => 'checkbox',
                    'options' => ['Dekorasi', 'Perencanaan Acara', 'Peralatan', 'Dokumentasi', 'Koordinasi Acara'],
                ],
                [
                    'name' => 'Menyediakan Paket Lengkap',
                    'slug' => 'bank-jasa-paket-event',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'pertanian' => [
                [
                    'name' => 'Jenis Layanan Pertanian',
                    'slug' => 'bank-jasa-jenis-pertanian',
                    'type' => 'checkbox',
                    'options' => ['Bajak Sawah', 'Traktor', 'Penyemprotan', 'Pemupukan', 'Panen', 'Pengolahan Lahan'],
                ],
                [
                    'name' => 'Jenis Lahan',
                    'slug' => 'bank-jasa-jenis-lahan',
                    'type' => 'select',
                    'options' => ['Sawah', 'Kebun', 'Ladang', 'Perkebunan'],
                ],
                [
                    'name' => 'Menyediakan Peralatan Sendiri',
                    'slug' => 'bank-jasa-peralatan-pertanian',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],

            'profesional' => [
                [
                    'name' => 'Bidang Keahlian',
                    'slug' => 'bank-jasa-bidang-keahlian',
                    'type' => 'text',
                ],
                [
                    'name' => 'Metode Konsultasi',
                    'slug' => 'bank-jasa-metode-konsultasi',
                    'type' => 'select',
                    'options' => ['Tatap Muka', 'Online', 'Keduanya'],
                ],
                [
                    'name' => 'Memiliki Sertifikasi',
                    'slug' => 'bank-jasa-sertifikasi',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
                [
                    'name' => 'Pengalaman Profesional (Tahun)',
                    'slug' => 'bank-jasa-pengalaman-profesional',
                    'type' => 'number',
                ],
            ],

            'pertukangan' => [
                [
                    'name' => 'Jenis Pekerjaan Pertukangan',
                    'slug' => 'bank-jasa-jenis-pertukangan',
                    'type' => 'checkbox',
                    'options' => ['Las', 'Bubut', 'Pembuatan Furniture', 'Pemasangan Pintu', 'Pembuatan Kanopi', 'Perbaikan Perabot'],
                ],
                [
                    'name' => 'Jenis Material',
                    'slug' => 'bank-jasa-material-pertukangan',
                    'type' => 'checkbox',
                    'options' => ['Kayu', 'Besi', 'Stainless Steel', 'Aluminium', 'Baja', 'Lainnya'],
                ],
                [
                    'name' => 'Bisa Pengerjaan di Lokasi',
                    'slug' => 'bank-jasa-pengerjaan-lokasi',
                    'type' => 'radio',
                    'options' => ['Ya', 'Tidak'],
                ],
            ],
        ];

        DB::transaction(function () use ($groups) {
            $total = 0;

            foreach ($groups as $group => $questions) {
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
                    // Tidak mengubah konfigurasi atribut yang sudah ada.
                    $attribute->update([
                        'is_recommended' => true,
                        'recommendation_group' => $group,
                    ]);

                    foreach (($data['options'] ?? []) as $optionIndex => $option) {
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

                    $total++;
                }
            }
        });

        $totalGroups = count($groups);
        $totalQuestions = collect($groups)->sum(fn ($questions) => count($questions));

        $this->command?->info(
            "{$totalQuestions} rekomendasi pertanyaan jasa dari {$totalGroups} kelompok berhasil disinkronkan."
        );
    }
}