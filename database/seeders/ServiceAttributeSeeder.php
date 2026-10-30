<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'jasa')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Daftar Jenis Jasa dan Pertanyaan
        |--------------------------------------------------------------------------
        |
        | Format pertanyaan:
        | name     = Label pertanyaan
        | type     = text, number, textarea, date, select, checkbox, radio
        | required = Wajib diisi atau tidak
        | options  = Pilihan jawaban jika menggunakan select/radio/checkbox
        |
        */

        $services = [

            // 1. SERVIS
            'servis' => [
                'name' => 'Servis',
                'questions' => [
                    ['name' => 'Jenis Peralatan', 'type' => 'select', 'required' => true, 'options' => ['AC Split', 'AC Central', 'Kulkas', 'Mesin Cuci', 'Televisi', 'Komputer', 'Laptop', 'Pompa Air', 'Lainnya']],
                    ['name' => 'Merek yang Dilayani', 'type' => 'text'],
                    ['name' => 'Jenis Kerusakan', 'type' => 'textarea'],
                    ['name' => 'Menerima Panggilan', 'type' => 'radio', 'required' => true, 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 2. BANGUNAN
            'bangunan' => [
                'name' => 'Bangunan',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'select', 'required' => true, 'options' => ['Bangun Rumah', 'Renovasi Rumah', 'Pengecatan', 'Pemasangan Keramik', 'Pemasangan Plafon', 'Pembuatan Pagar', 'Instalasi Listrik', 'Instalasi Air', 'Lainnya']],
                    ['name' => 'Skala Proyek', 'type' => 'select', 'options' => ['Kecil', 'Menengah', 'Besar']],
                    ['name' => 'Sistem Pengerjaan', 'type' => 'radio', 'required' => true, 'options' => ['Borongan', 'Harian', 'Keduanya']],
                    ['name' => 'Penyediaan Material', 'type' => 'radio', 'options' => ['Penyedia Jasa', 'Pemilik Proyek', 'Bisa Keduanya']],
                ],
            ],

            // 3. TRANSPORTASI
            'transportasi' => [
                'name' => 'Transportasi',
                'questions' => [
                    ['name' => 'Jenis Kendaraan', 'type' => 'select', 'required' => true, 'options' => ['Sepeda Motor', 'Mobil', 'Pickup', 'Minibus', 'Bus', 'Truk', 'Lainnya']],
                    ['name' => 'Kapasitas Penumpang', 'type' => 'number'],
                    ['name' => 'Jenis Layanan', 'type' => 'select', 'options' => ['Antar Jemput', 'Carter', 'Wisata', 'Angkutan Barang', 'Pindahan']],
                    ['name' => 'Menerima Pesanan Luar Kota', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 4. PENDIDIKAN
            'pendidikan' => [
                'name' => 'Pendidikan',
                'questions' => [
                    ['name' => 'Mata Pelajaran atau Bidang', 'type' => 'text', 'required' => true],
                    ['name' => 'Jenjang Pendidikan', 'type' => 'select', 'options' => ['PAUD / TK', 'SD / MI', 'SMP / MTs', 'SMA / SMK / MA', 'Perguruan Tinggi', 'Umum']],
                    ['name' => 'Metode Pembelajaran', 'type' => 'select', 'options' => ['Tatap Muka', 'Online', 'Keduanya']],
                    ['name' => 'Sasaran Peserta', 'type' => 'text'],
                ],
            ],

            // 5. SERVIS ELEKTRONIK
            'servis-elektronik' => [
                'name' => 'Servis Elektronik',
                'questions' => [
                    ['name' => 'Perangkat yang Diperbaiki', 'type' => 'select', 'required' => true, 'options' => ['Televisi', 'Kulkas', 'Mesin Cuci', 'Speaker', 'Amplifier', 'Kipas Angin', 'Rice Cooker', 'Peralatan Dapur', 'Lainnya']],
                    ['name' => 'Merek Perangkat', 'type' => 'text'],
                    ['name' => 'Garansi Perbaikan', 'type' => 'radio', 'options' => ['Ada', 'Tidak Ada']],
                ],
            ],

            // 6. SERVIS AC
            'servis-ac' => [
                'name' => 'Servis AC',
                'questions' => [
                    ['name' => 'Jenis Layanan AC', 'type' => 'checkbox', 'required' => true, 'options' => ['Cuci AC', 'Isi Freon', 'Perbaikan AC', 'Pasang AC', 'Bongkar AC', 'Perawatan Rutin']],
                    ['name' => 'Jenis AC', 'type' => 'select', 'options' => ['Split', 'Central', 'Standing Floor', 'Cassette', 'Lainnya']],
                    ['name' => 'Menerima Kontrak Perawatan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 7. KOMPUTER DAN JARINGAN
            'komputer-jaringan' => [
                'name' => 'Komputer dan Jaringan',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'required' => true, 'options' => ['Instalasi Windows', 'Perbaikan Laptop', 'Perbaikan Komputer', 'Perakitan PC', 'Instalasi Jaringan', 'Konfigurasi Router', 'Pemulihan Data']],
                    ['name' => 'Menerima Panggilan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Melayani Perusahaan atau Kantor', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 8. TUKANG LAS DAN BUBUT
            'las-bubut' => [
                'name' => 'Las dan Bubut',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'checkbox', 'options' => ['Pagar', 'Teralis', 'Kanopi', 'Pintu Besi', 'Railing Tangga', 'Bubut Logam', 'Rangka Baja', 'Lainnya']],
                    ['name' => 'Jenis Material', 'type' => 'checkbox', 'options' => ['Besi', 'Stainless Steel', 'Aluminium', 'Baja', 'Lainnya']],
                    ['name' => 'Bisa Pengerjaan di Lokasi', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 9. SUMUR BOR DAN POMPA
            'sumur-pompa' => [
                'name' => 'Sumur Bor dan Pompa Air',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'checkbox', 'options' => ['Sumur Bor', 'Sumur Gali', 'Pemasangan Pompa', 'Perbaikan Pompa', 'Pembersihan Sumur', 'Instalasi Pipa']],
                    ['name' => 'Jenis Pompa', 'type' => 'select', 'options' => ['Jet Pump', 'Submersible', 'Pompa Sumur Dangkal', 'Pompa Celup', 'Lainnya']],
                    ['name' => 'Menerima Survei Lokasi', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 10. PLUMBING
            'plumbing' => [
                'name' => 'Plumbing dan Instalasi Air',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'checkbox', 'options' => ['Pemasangan Pipa', 'Perbaikan Kebocoran', 'Instalasi Kamar Mandi', 'Saluran Air', 'Tandon Air', 'Water Heater']],
                    ['name' => 'Jenis Bangunan', 'type' => 'select', 'options' => ['Rumah', 'Kantor', 'Hotel', 'Pabrik', 'Bangunan Komersial']],
                    ['name' => 'Menerima Pekerjaan Darurat', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 11. SEDOT WC
            'sedot-wc' => [
                'name' => 'Sedot WC dan Septic Tank',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Sedot WC', 'Pembersihan Septic Tank', 'Perbaikan Saluran Mampet', 'Pembuatan Septic Tank', 'Penyedotan Limbah']],
                    ['name' => 'Jenis Lokasi', 'type' => 'select', 'options' => ['Rumah', 'Kantor', 'Restoran', 'Pabrik', 'Tempat Umum']],
                    ['name' => 'Layanan Darurat', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 12. KEBERSIHAN
            'kebersihan' => [
                'name' => 'Jasa Kebersihan',
                'questions' => [
                    ['name' => 'Jenis Kebersihan', 'type' => 'checkbox', 'options' => ['Rumah', 'Kantor', 'Kaca Gedung', 'Sofa', 'Kasur', 'Karpet', 'Pasca Renovasi']],
                    ['name' => 'Sistem Pengerjaan', 'type' => 'select', 'options' => ['Per Jam', 'Harian', 'Borongan', 'Kontrak']],
                    ['name' => 'Menyediakan Peralatan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 13. LAUNDRY
            'laundry' => [
                'name' => 'Laundry dan Cuci',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Cuci Kering', 'Cuci Setrika', 'Setrika Saja', 'Laundry Sepatu', 'Laundry Karpet', 'Laundry Bed Cover']],
                    ['name' => 'Layanan Antar Jemput', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Waktu Penyelesaian', 'type' => 'select', 'options' => ['Reguler', 'Express', 'Same Day']],
                ],
            ],

            // 14. PENGENDALIAN HAMA
            'pengendalian-hama' => [
                'name' => 'Pengendalian Hama',
                'questions' => [
                    ['name' => 'Jenis Hama', 'type' => 'checkbox', 'options' => ['Rayap', 'Tikus', 'Nyamuk', 'Kecoa', 'Semut', 'Ular', 'Lalat', 'Lainnya']],
                    ['name' => 'Jenis Lokasi', 'type' => 'select', 'options' => ['Rumah', 'Gedung', 'Gudang', 'Restoran', 'Lahan Pertanian']],
                    ['name' => 'Menyediakan Garansi', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 15. PERTAMANAN
            'pertamanan' => [
                'name' => 'Pertamanan dan Landscape',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'checkbox', 'options' => ['Potong Rumput', 'Pembuatan Taman', 'Perawatan Tanaman', 'Pemangkasan Pohon', 'Pembuatan Kolam', 'Landscape']],
                    ['name' => 'Menyediakan Tanaman', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Menerima Perawatan Berkala', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 16. DEREK DAN TOWING
            'derek-towing' => [
                'name' => 'Derek dan Towing',
                'questions' => [
                    ['name' => 'Jenis Kendaraan yang Dilayani', 'type' => 'checkbox', 'options' => ['Motor', 'Mobil', 'Pickup', 'Truk', 'Bus']],
                    ['name' => 'Jenis Layanan', 'type' => 'select', 'options' => ['Derek', 'Towing', 'Bantuan Mogok', 'Antar Kendaraan']],
                    ['name' => 'Layanan 24 Jam', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 17. JASA PINDAHAN
            'jasa-pindahan' => [
                'name' => 'Jasa Pindahan',
                'questions' => [
                    ['name' => 'Jenis Pindahan', 'type' => 'select', 'options' => ['Rumah', 'Kantor', 'Kos', 'Toko', 'Gudang']],
                    ['name' => 'Layanan Pengemasan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Menyediakan Tenaga Angkut', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 18. FOTOGRAFI DAN VIDEO
            'fotografi-video' => [
                'name' => 'Fotografi dan Videografi',
                'questions' => [
                    ['name' => 'Jenis Dokumentasi', 'type' => 'checkbox', 'options' => ['Pernikahan', 'Prewedding', 'Wisuda', 'Produk', 'Acara', 'Company Profile', 'Drone']],
                    ['name' => 'Peralatan Drone', 'type' => 'radio', 'options' => ['Tersedia', 'Tidak Tersedia']],
                    ['name' => 'Hasil Digital dan Cetak', 'type' => 'checkbox', 'options' => ['File Digital', 'Album Foto', 'Video Editing', 'Cetak Foto']],
                ],
            ],

            // 19. MAKEUP DAN KECANTIKAN
            'makeup-kecantikan' => [
                'name' => 'Makeup dan Kecantikan',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Makeup Pengantin', 'Makeup Wisuda', 'Makeup Pesta', 'Hairdo', 'Perawatan Wajah', 'Rias Tradisional']],
                    ['name' => 'Menerima Panggilan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Menyediakan Paket Pengantin', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 20. JAHIT DAN PERMAK
            'jahit-permak' => [
                'name' => 'Jahit dan Permak Pakaian',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Jahit Pakaian', 'Permak Celana', 'Permak Baju', 'Kebaya', 'Seragam', 'Jas', 'Bordir']],
                    ['name' => 'Menerima Pesanan Khusus', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Menyediakan Bahan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 21. KATERING
            'katering' => [
                'name' => 'Katering dan Konsumsi',
                'questions' => [
                    ['name' => 'Jenis Katering', 'type' => 'checkbox', 'options' => ['Harian', 'Pernikahan', 'Rapat', 'Nasi Kotak', 'Prasmanan', 'Snack Box']],
                    ['name' => 'Kapasitas Pesanan', 'type' => 'number'],
                    ['name' => 'Menyediakan Layanan Prasmanan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 22. EVENT
            'event-organizer' => [
                'name' => 'Event Organizer dan Dekorasi',
                'questions' => [
                    ['name' => 'Jenis Acara', 'type' => 'checkbox', 'options' => ['Pernikahan', 'Khitanan', 'Ulang Tahun', 'Seminar', 'Gathering', 'Pentas Seni', 'Acara Pemerintahan']],
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Dekorasi', 'Perencanaan Acara', 'Peralatan', 'Dokumentasi', 'Koordinasi Acara']],
                    ['name' => 'Menyediakan Paket Lengkap', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 23. SOUND SYSTEM
            'sound-system' => [
                'name' => 'Sound System dan Lighting',
                'questions' => [
                    ['name' => 'Jenis Peralatan', 'type' => 'checkbox', 'options' => ['Sound System', 'Panggung', 'Lighting', 'LED Screen', 'Genset', 'Karaoke']],
                    ['name' => 'Kapasitas Acara', 'type' => 'select', 'options' => ['Kecil', 'Menengah', 'Besar']],
                    ['name' => 'Termasuk Operator', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 24. DIGITAL DAN KREATIF
            'digital-kreatif' => [
                'name' => 'Digital dan Kreatif',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Website', 'Aplikasi Mobile', 'Desain Grafis', 'Logo', 'Video Editing', 'Digital Marketing', 'SEO', 'Pengelolaan Media Sosial']],
                    ['name' => 'Model Pengerjaan', 'type' => 'select', 'options' => ['Per Proyek', 'Bulanan', 'Per Jam']],
                    ['name' => 'Bisa Dikerjakan Online', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 25. PERCETAKAN
            'percetakan' => [
                'name' => 'Percetakan dan Sablon',
                'questions' => [
                    ['name' => 'Jenis Produk', 'type' => 'checkbox', 'options' => ['Banner', 'Spanduk', 'Undangan', 'Brosur', 'Kartu Nama', 'Stiker', 'Sablon Kaos', 'Plakat']],
                    ['name' => 'Jenis Pesanan', 'type' => 'select', 'options' => ['Satuan', 'Grosir', 'Keduanya']],
                    ['name' => 'Menerima Desain dari Pelanggan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 26. PERTANIAN
            'jasa-pertanian' => [
                'name' => 'Jasa Pertanian',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Bajak Sawah', 'Traktor', 'Penyemprotan', 'Pemupukan', 'Panen', 'Pengolahan Lahan', 'Pemangkasan']],
                    ['name' => 'Jenis Lahan', 'type' => 'select', 'options' => ['Sawah', 'Kebun', 'Ladang', 'Perkebunan']],
                    ['name' => 'Menyediakan Alat Sendiri', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 27. PETERNAKAN
            'jasa-peternakan' => [
                'name' => 'Jasa Peternakan',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Perawatan Ternak', 'Inseminasi Buatan', 'Pemeriksaan Ternak', 'Pembuatan Kandang', 'Pembersihan Kandang']],
                    ['name' => 'Jenis Ternak', 'type' => 'checkbox', 'options' => ['Sapi', 'Domba', 'Kambing', 'Ayam', 'Bebek', 'Kelinci']],
                    ['name' => 'Menerima Kunjungan ke Lokasi', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 28. PERAWATAN HEWAN
            'perawatan-hewan' => [
                'name' => 'Perawatan Hewan',
                'questions' => [
                    ['name' => 'Jenis Hewan', 'type' => 'checkbox', 'options' => ['Kucing', 'Anjing', 'Burung', 'Kelinci', 'Reptil', 'Lainnya']],
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Grooming', 'Mandi Hewan', 'Penitipan', 'Antar Jemput', 'Perawatan Harian']],
                    ['name' => 'Layanan Kunjungan Rumah', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 29. INSTALASI CCTV
            'instalasi-cctv' => [
                'name' => 'Instalasi CCTV dan Keamanan',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Pemasangan CCTV', 'Perbaikan CCTV', 'Konfigurasi Online', 'Alarm', 'Access Control', 'Instalasi IP Camera']],
                    ['name' => 'Jenis CCTV', 'type' => 'select', 'options' => ['Analog', 'IP Camera', 'Wireless', 'Keduanya']],
                    ['name' => 'Menyediakan Peralatan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 30. INSTALASI LISTRIK
            'instalasi-listrik' => [
                'name' => 'Instalasi Listrik',
                'questions' => [
                    ['name' => 'Jenis Pekerjaan', 'type' => 'checkbox', 'options' => ['Instalasi Rumah', 'Panel Listrik', 'Perbaikan Korsleting', 'Pemasangan Lampu', 'Instalasi Gedung', 'Grounding']],
                    ['name' => 'Jenis Bangunan', 'type' => 'select', 'options' => ['Rumah', 'Kantor', 'Toko', 'Pabrik', 'Gedung']],
                    ['name' => 'Menerima Pekerjaan Darurat', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 31. ALAT BERAT
            'alat-berat' => [
                'name' => 'Sewa dan Operator Alat Berat',
                'questions' => [
                    ['name' => 'Jenis Alat Berat', 'type' => 'checkbox', 'options' => ['Excavator', 'Bulldozer', 'Wheel Loader', 'Vibro Roller', 'Crane', 'Dump Truck', 'Concrete Mixer']],
                    ['name' => 'Termasuk Operator', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Sistem Sewa', 'type' => 'select', 'options' => ['Per Jam', 'Harian', 'Mingguan', 'Bulanan']],
                ],
            ],

            // 32. KUNCI DAN AKSES
            'kunci-keamanan' => [
                'name' => 'Ahli Kunci dan Keamanan',
                'questions' => [
                    ['name' => 'Jenis Layanan', 'type' => 'checkbox', 'options' => ['Duplikat Kunci', 'Perbaikan Kunci', 'Ganti Silinder', 'Kunci Kendaraan', 'Instalasi Smart Lock']],
                    ['name' => 'Menerima Panggilan Darurat', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                    ['name' => 'Layanan 24 Jam', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],

            // 33. JASA KONSULTASI
            'konsultasi-profesional' => [
                'name' => 'Konsultasi Profesional',
                'questions' => [
                    ['name' => 'Bidang Konsultasi', 'type' => 'select', 'options' => ['Akuntansi', 'Perpajakan', 'Bisnis', 'Manajemen', 'Penerjemahan', 'Administrasi', 'Teknologi Informasi']],
                    ['name' => 'Metode Konsultasi', 'type' => 'select', 'options' => ['Tatap Muka', 'Online', 'Keduanya']],
                    ['name' => 'Menyediakan Konsultasi Perusahaan', 'type' => 'radio', 'options' => ['Ya', 'Tidak']],
                ],
            ],
        ];

        DB::transaction(function () use ($category, $services) {

            foreach ($services as $subcategorySlug => $service) {

                $subcategory = SubCategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => $subcategorySlug,
                    ],
                    [
                        'name' => $service['name'],
                        'is_active' => true,
                    ]
                );

                foreach ($service['questions'] as $index => $question) {

                    $attributeSlug = $subcategorySlug . '-' . Str::slug($question['name']);

                    $attribute = ProductAttribute::updateOrCreate(
                        ['slug' => $attributeSlug],
                        [
                            'name' => $question['name'],
                            'type' => $question['type'],
                            'filterable' => true,
                            'required' => false,
                            'searchable' => true,
                            'sort' => $index + 1,
                        ]
                    );

                    foreach (($question['options'] ?? []) as $optionIndex => $option) {
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

                    SubCategoryAttribute::updateOrCreate(
                        [
                            'sub_category_id' => $subcategory->id,
                            'product_attribute_id' => $attribute->id,
                        ],
                        [
                            'required' => $question['required'] ?? false,
                            'filterable' => true,
                            'searchable' => true,
                            'visible' => true,
                            'sort' => $index + 1,
                        ]
                    );
                }
            }
        });
    }
}