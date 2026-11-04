<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ServiceDetail;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FjbDummySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $serviceCategory = Category::where('name', 'Jasa')->firstOrFail();
            $otherCategory = Category::where('name', 'Lainnya')->firstOrFail();
            $jobCategory = Category::where('name', 'Lowongan Kerja')->first();

            $excludedCategoryIds = [$serviceCategory->id, $otherCategory->id];
            if ($jobCategory) {
                $excludedCategoryIds[] = $jobCategory->id;
            }

            $productSubCategories = SubCategory::whereNotIn('category_id', $excludedCategoryIds)
                ->orderBy('id')->get();
            $serviceSubCategories = SubCategory::where('category_id', $serviceCategory->id)
                ->orderBy('id')->get();
            $otherSubCategory = SubCategory::where('category_id', $otherCategory->id)
                ->orderBy('id')->firstOrFail();

            if ($productSubCategories->isEmpty() || $serviceSubCategories->isEmpty()) {
                throw new \RuntimeException('Subkategori produk atau jasa belum tersedia.');
            }

            $sellerNames = [
                'Andi Saputra', 'Dedi Firmansyah', 'Budi Santoso', 'Asep Hidayat',
                'Rina Marlina', 'Siti Nurhaliza', 'Rahmat Hidayat', 'Yudi Setiawan',
                'Agus Kurniawan', 'Dian Puspitasari', 'Rudi Hartono', 'Eko Prasetyo',
                'Taufik Hidayat', 'Lina Marlina', 'Hendra Gunawan', 'Wawan Setiawan',
                'Fitri Handayani', 'Iwan Kurnia', 'Nina Amelia', 'Fajar Ramadhan',
            ];

            $storeNames = [
                'Andi Motor Kuningan', 'Dedi Property', 'Budi Elektronik', 'Asep Bangunan',
                'Rina Fashion Store', 'Dapur Siti', 'Rahmat Teknik', 'Yudi Transport',
                'Agus Jaya Store', 'Dian Collection', 'Rudi Makmur', 'Eko Sejahtera',
                'Taufik Mandiri', 'Lina Shop', 'Hendra Jaya', 'Wawan Berkah',
                'Fitri Store', 'Iwan Tani', 'Nina Craft', 'Fajar Digital',
            ];

            $users = [];
            $stores = [];

            foreach ($sellerNames as $i => $sellerName) {
                $number = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                $user = User::firstOrCreate(
                    ['email' => "dummy-fjb-penjual{$number}@example.test"],
                    ['name' => $sellerName, 'password' => 'DummyFJB#2026!']
                );

                // account_type tidak termasuk $fillable pada model User.
                $user->forceFill(['account_type' => 'seller'])->save();

                $store = Store::updateOrCreate(
                    ['slug' => "dummy-fjb-toko-{$number}"],
                    [
                        'user_id' => $user->id,
                        'name' => $storeNames[$i],
                        'description' => "Toko {$storeNames[$i]} melayani pembelian dan pemesanan di wilayah Kuningan.",
                        'phone' => '08120000' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                        'whatsapp' => '08130000' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                        'address' => 'Kabupaten Kuningan, Jawa Barat',
                        'rating' => 4 + (($i % 10) / 10),
                        'total_reviews' => 5 + ($i * 3),
                        'followers' => 10 + ($i * 12),
                        'verified' => $i % 4 === 0,
                        'is_open' => true,
                    ]
                );

                $users[] = $user;
                $stores[] = $store;
            }

            // 75 listing produk.
            foreach (range(0, 74) as $i) {
                $sub = $productSubCategories[$i % $productSubCategories->count()];
                $seller = $i % count($users);
                $number = $i + 1;
                $status = $i % 5 === 0 ? 'pending' : 'published';

                $product = Product::updateOrCreate(
                    ['slug' => "dummy-fjb-produk-{$number}-" . Str::slug($sub->name)],
                    [
                        'user_id' => $users[$seller]->id,
                        'store_id' => $stores[$seller]->id,
                        'category_id' => $sub->category_id,
                        'sub_category_id' => $sub->id,
                        'title' => "Dijual {$sub->name} Berkualitas #{$number}",
                        'description' => "Produk {$sub->name} tersedia di Kabupaten Kuningan. Hubungi penjual untuk informasi lebih lanjut.",
                        'price' => (5 + (($i * 7) % 496)) * 100000,
                        'price_type' => 'fixed',
                        'condition' => $i % 2 === 0 ? 'new' : 'used',
                        'status' => $status,
                        'views' => ($i * 37) % 1501,
                        'published_at' => $status === 'published' ? now() : null,
                        'listing_type' => 'product',
                        'availability_status' => 'available',
                        'stock_quantity' => 1 + ($i % 30),
                    ]
                );

                $this->createAttributes($product);
            }

            // 23 listing jasa.
            foreach (range(0, 22) as $i) {
                $sub = $serviceSubCategories[$i % $serviceSubCategories->count()];
                $seller = ($i + 5) % count($users);
                $number = $i + 1;
                $price = (5 + ($i % 46)) * 50000;

                $product = Product::updateOrCreate(
                    ['slug' => "dummy-fjb-jasa-{$number}-" . Str::slug($sub->name)],
                    [
                        'user_id' => $users[$seller]->id,
                        'store_id' => $stores[$seller]->id,
                        'category_id' => $sub->category_id,
                        'sub_category_id' => $sub->id,
                        'title' => "Jasa {$sub->name} Profesional #{$number}",
                        'description' => "Melayani jasa {$sub->name} untuk wilayah Kabupaten Kuningan dan sekitarnya.",
                        'price' => $price,
                        'price_type' => 'fixed',
                        'condition' => null,
                        'status' => 'published',
                        'views' => ($i * 41) % 1001,
                        'published_at' => now(),
                        'listing_type' => 'service',
                        'availability_status' => 'available',
                        'stock_quantity' => 1,
                    ]
                );

                ServiceDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'pricing_type' => 'fixed',
                        'price_from' => $price,
                        'price_to' => null,
                        'price_unit' => 'per pekerjaan',
                        'experience_years' => 1 + ($i % 15),
                        'schedule' => [
                            'senin' => true, 'selasa' => true, 'rabu' => true,
                            'kamis' => true, 'jumat' => true, 'sabtu' => true,
                            'minggu' => false,
                        ],
                        'service_description' => $product->description,
                    ]
                );

                $this->createAttributes($product);
            }

            // 2 listing kategori Lainnya.
            foreach (range(1, 2) as $number) {
                $seller = $number - 1;

                Product::updateOrCreate(
                    ['slug' => "dummy-fjb-lainnya-{$number}"],
                    [
                        'user_id' => $users[$seller]->id,
                        'store_id' => $stores[$seller]->id,
                        'category_id' => $otherCategory->id,
                        'sub_category_id' => $otherSubCategory->id,
                        'title' => "Informasi dan Penawaran Lainnya #{$number}",
                        'description' => 'Informasi penawaran lainnya di wilayah Kabupaten Kuningan.',
                        'price' => null,
                        'price_type' => 'negotiable',
                        'condition' => null,
                        'status' => 'published',
                        'views' => 100 + ($number * 25),
                        'published_at' => now(),
                        'listing_type' => 'other',
                        'availability_status' => 'available',
                        'stock_quantity' => 1,
                    ]
                );
            }
        });

        $this->command?->info('Seeder FJB berhasil dijalankan.');
        $this->command?->info('20 penjual, 20 toko, dan 100 listing dummy telah diproses.');
    }

    private function createAttributes(Product $product): void
    {
        $mappings = DB::table('sub_category_attributes')
            ->where('sub_category_id', $product->sub_category_id)
            ->where('listing_type', $product->listing_type)
            ->orderBy('sort')
            ->get();

        foreach ($mappings as $mapping) {
            $attribute = ProductAttribute::find($mapping->product_attribute_id);
            if (! $attribute) {
                continue;
            }

            $options = DB::table('product_attribute_options')
                ->where('product_attribute_id', $attribute->id)
                ->orderBy('sort')
                ->pluck('value')
                ->toArray();

            $type = strtolower($attribute->type);

            if (in_array($type, ['select', 'radio', 'dropdown'], true)) {
                $value = $options[0] ?? 'Lainnya';
            } elseif ($type === 'checkbox') {
                $value = json_encode(array_slice($options, 0, min(2, count($options))), JSON_UNESCAPED_UNICODE);
            } elseif (in_array($type, ['number', 'integer'], true)) {
                $value = (string) (1 + ($product->id % 20));
            } elseif (in_array($type, ['boolean', 'toggle'], true)) {
                $value = '1';
            } else {
                $value = "Informasi {$attribute->name} dummy";
            }

            ProductAttributeValue::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'product_attribute_id' => $attribute->id,
                ],
                ['value' => $value]
            );
        }
    }
}