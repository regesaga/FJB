<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductLocation;
use App\Models\Regency;
use App\Models\ServiceArea;
use App\Models\ServiceDetail;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use App\Models\Village;
use App\Models\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller
{
    /**
     * Membuat iklan barang atau jasa.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'listing_type' => ['required', 'in:product,service'],

            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sub_category_id' => ['required', 'integer', 'exists:sub_categories,id'],

            'store_id' => ['nullable', 'integer', 'exists:stores,id'],

            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'price_type' => ['nullable', 'in:fixed,negotiable'],
            'condition' => ['nullable', 'string', 'max:30'],
            'availability_status' => [
                'nullable',
                'in:available,reserved,sold,unavailable',
            ],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],

            'attributes' => ['nullable', 'array'],

            'location' => ['required', 'array'],
            'location.province_code' => [
                'required',
                'string',
                'exists:provinces,code',
            ],
            'location.regency_code' => [
                'required',
                'string',
                'exists:regencies,code',
            ],
            'location.district_code' => [
                'required',
                'string',
                'exists:districts,code',
            ],
            'location.village_code' => [
                'required',
                'string',
                'exists:villages,code',
            ],
            'location.address_detail' => ['nullable', 'string', 'max:2000'],
            'location.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'location.longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            // Detail khusus jasa
            'service_detail' => ['required_if:listing_type,service', 'array'],
            'service_detail.pricing_type' => [
                'required_if:listing_type,service',
                'in:fixed,negotiable',
            ],
            'service_detail.price_from' => ['nullable', 'numeric', 'min:0'],
            'service_detail.price_to' => ['nullable', 'numeric', 'min:0'],
            'service_detail.price_unit' => ['nullable', 'string', 'max:50'],
            'service_detail.experience_years' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            'service_detail.schedule' => ['nullable', 'array'],
            'service_detail.service_description' => ['nullable', 'string'],

            // Wilayah layanan jasa
            'service_areas' => ['required_if:listing_type,service', 'array', 'min:1'],
            'service_areas.*' => ['required', 'array'],
            'service_areas.*.village_code' => ['nullable', 'string'],
            'service_areas.*.district_code' => ['nullable', 'string'],
            'service_areas.*.regency_code' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        /*
         * Pastikan kategori dan subkategori saling berhubungan.
         */
        $category = Category::query()
            ->where('is_active', true)
            ->findOrFail($validated['category_id']);

        $subCategory = SubCategory::query()
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->find($validated['sub_category_id']);

        if (!$subCategory) {
            throw ValidationException::withMessages([
                'sub_category_id' => [
                    'Subkategori tidak sesuai dengan kategori yang dipilih.',
                ],
            ]);
        }

        /*
         * Kategori Jasa hanya untuk listing_type service.
         */
        if (
            ($category->slug === 'jasa' && $validated['listing_type'] !== 'service')
            || ($category->slug !== 'jasa' && $validated['listing_type'] === 'service')
        ) {
            throw ValidationException::withMessages([
                'listing_type' => [
                    'Jenis iklan tidak sesuai dengan kategori yang dipilih.',
                ],
            ]);
        }

        /*
         * Pastikan toko benar-benar milik pengguna yang login.
         */
        if (!empty($validated['store_id'])) {
            $storeOwned = Store::query()
                ->where('id', $validated['store_id'])
                ->where('user_id', $user->id)
                ->exists();

            if (!$storeOwned) {
                throw ValidationException::withMessages([
                    'store_id' => ['Toko bukan milik akun Anda.'],
                ]);
            }
        }

        /*
         * Validasi hierarki lokasi utama.
         */
        $location = $validated['location'];

        $validRegency = Regency::query()
            ->where('code', $location['regency_code'])
            ->where('province_code', $location['province_code'])
            ->exists();

        $validDistrict = District::query()
            ->where('code', $location['district_code'])
            ->where('regency_code', $location['regency_code'])
            ->exists();

        $validVillage = Village::query()
            ->where('code', $location['village_code'])
            ->where('district_code', $location['district_code'])
            ->exists();

        if (!$validRegency || !$validDistrict || !$validVillage) {
            throw ValidationException::withMessages([
                'location' => [
                    'Kombinasi provinsi, kabupaten, kecamatan, dan desa tidak sesuai.',
                ],
            ]);
        }

        /*
         * Validasi pertanyaan dinamis berdasarkan subkategori.
         */
        $this->validateDynamicAttributes(
            $validated['attributes'] ?? [],
            $subCategory->id
        );

        /*
         * Validasi wilayah layanan khusus jasa.
         */
        if ($validated['listing_type'] === 'service') {
            $this->validateServiceAreas($validated['service_areas']);
        }

        /*
         * Simpan data dan foto.
         * Jika transaksi gagal, file yang sudah tersimpan ikut dibersihkan.
         */
        $storedPaths = [];

        try {
            $product = DB::transaction(function () use (
                $request,
                $validated,
                $user,
                $location,
                &$storedPaths
            ) {
                $product = Product::create([
                    'user_id' => $user->id,
                    'store_id' => $validated['store_id'] ?? null,
                    'category_id' => $validated['category_id'],
                    'sub_category_id' => $validated['sub_category_id'],

                    'title' => $validated['title'],
                    'slug' => $this->generateUniqueSlug($validated['title']),
                    'description' => $validated['description'] ?? null,

                    'price' => $validated['listing_type'] === 'product'
                        ? ($validated['price'] ?? null)
                        : null,

                    'price_type' => $validated['price_type']
                        ?? 'negotiable',

                    'condition' => $validated['condition'] ?? null,

                    'listing_type' => $validated['listing_type'],
                    'availability_status' => $validated['availability_status']
                        ?? 'available',
                    'stock_quantity' => $validated['stock_quantity'] ?? 1,

                    // Semua iklan baru harus melalui moderasi.
                    'status' => 'draft',
                    'published_at' => null,
                ]);

                /*
                 * Simpan lokasi utama.
                 */
                ProductLocation::create([
                    'product_id' => $product->id,
                    'province_code' => $location['province_code'],
                    'regency_code' => $location['regency_code'],
                    'district_code' => $location['district_code'],
                    'village_code' => $location['village_code'],
                    'address_detail' => $location['address_detail'] ?? null,
                    'latitude' => $location['latitude'] ?? null,
                    'longitude' => $location['longitude'] ?? null,
                ]);

                /*
                 * Simpan jawaban atribut dinamis.
                 */
                foreach (($validated['attributes'] ?? []) as $attributeId => $value) {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'product_attribute_id' => $attributeId,
                        'value' => is_array($value)
                            ? json_encode($value, JSON_UNESCAPED_UNICODE)
                            : (string) $value,
                    ]);
                }

                /*
                 * Simpan foto.
                 */
                $images = $request->file('images', []);

                foreach ($images as $index => $image) {
                    $path = $image->store('products', 'public');

                    $storedPaths[] = $path;

                    $product->images()->create([
                        'image_path' => $path,
                        'alt_text' => $validated['title'],
                        'sort_order' => $index,
                        'is_primary' => $index === 0,
                    ]);
                }

                /*
                 * Simpan detail dan wilayah khusus jasa.
                 */
                if ($validated['listing_type'] === 'service') {
                    $detail = $validated['service_detail'];

                    ServiceDetail::create([
                        'product_id' => $product->id,
                        'pricing_type' => $detail['pricing_type'],
                        'price_from' => $detail['price_from'] ?? null,
                        'price_to' => $detail['price_to'] ?? null,
                        'price_unit' => $detail['price_unit'] ?? null,
                        'experience_years' => $detail['experience_years'] ?? null,
                        'schedule' => $detail['schedule'] ?? null,
                        'service_description' => $detail['service_description'] ?? null,
                    ]);

                    foreach ($validated['service_areas'] as $area) {
                        ServiceArea::create([
                            'product_id' => $product->id,
                            'village_code' => $area['village_code'] ?? null,
                            'district_code' => $area['district_code'] ?? null,
                            'regency_code' => $area['regency_code'] ?? null,
                        ]);
                    }
                }

                return $product;
            });
        } catch (Throwable $exception) {
            if (!empty($storedPaths)) {
                Storage::disk('public')->delete($storedPaths);
            }

            throw $exception;
        }

        $product->load([
            'category',
            'subCategory',
            'images',
            'location',
            'attributeValues.attribute',
            'serviceDetail',
            'serviceAreas',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil disimpan sebagai draft.',
            'data' => $product,
        ], 201);
    }

    /**
     * Memvalidasi jawaban berdasarkan pertanyaan subkategori.
     */
    private function validateDynamicAttributes(
        array $answers,
        int $subCategoryId
    ): void {
        $mappings = SubCategoryAttribute::query()
            ->where('sub_category_id', $subCategoryId)
            ->where('visible', true)
            ->with('productAttribute.options')
            ->get();

        $allowedIds = $mappings
            ->pluck('product_attribute_id')
            ->map(fn ($id) => (string) $id)
            ->all();

        foreach (array_keys($answers) as $attributeId) {
            if (!in_array((string) $attributeId, $allowedIds, true)) {
                throw ValidationException::withMessages([
                    'attributes' => [
                        "Atribut {$attributeId} tidak tersedia pada subkategori ini.",
                    ],
                ]);
            }
        }

        foreach ($mappings as $mapping) {
            $attribute = $mapping->productAttribute;
            $attributeId = (string) $attribute->id;

            $exists = array_key_exists($attributeId, $answers)
                || array_key_exists($attribute->id, $answers);

            if (!$exists) {
                if ($mapping->required) {
                    throw ValidationException::withMessages([
                        "attributes.{$attributeId}" => [
                            "Pertanyaan {$attribute->name} wajib diisi.",
                        ],
                    ]);
                }

                continue;
            }

            $value = $answers[$attributeId] ?? $answers[$attribute->id];

            if (
                $value === null
                || $value === ''
                || (is_array($value) && count($value) === 0)
            ) {
                if ($mapping->required) {
                    throw ValidationException::withMessages([
                        "attributes.{$attributeId}" => [
                            "Pertanyaan {$attribute->name} wajib diisi.",
                        ],
                    ]);
                }

                continue;
            }

            $options = $attribute->options
                ->pluck('value')
                ->all();

            switch ($attribute->type) {
                case 'checkbox':
                    if (!is_array($value)) {
                        throw ValidationException::withMessages([
                            "attributes.{$attributeId}" => [
                                "Jawaban {$attribute->name} harus berupa daftar pilihan.",
                            ],
                        ]);
                    }

                    foreach ($value as $selected) {
                        if (!in_array($selected, $options, true)) {
                            throw ValidationException::withMessages([
                                "attributes.{$attributeId}" => [
                                    "Pilihan pada {$attribute->name} tidak valid.",
                                ],
                            ]);
                        }
                    }
                    break;

                case 'select':
                case 'radio':
                    if (
                        !is_string($value)
                        || !in_array($value, $options, true)
                    ) {
                        throw ValidationException::withMessages([
                            "attributes.{$attributeId}" => [
                                "Pilihan {$attribute->name} tidak valid.",
                            ],
                        ]);
                    }
                    break;

                case 'number':
                    if (!is_numeric($value)) {
                        throw ValidationException::withMessages([
                            "attributes.{$attributeId}" => [
                                "{$attribute->name} harus berupa angka.",
                            ],
                        ]);
                    }
                    break;

                case 'date':
                    if (
                        !is_string($value)
                        || !strtotime($value)
                    ) {
                        throw ValidationException::withMessages([
                            "attributes.{$attributeId}" => [
                                "Tanggal {$attribute->name} tidak valid.",
                            ],
                        ]);
                    }
                    break;

                case 'text':
                case 'textarea':
                    if (!is_string($value)) {
                        throw ValidationException::withMessages([
                            "attributes.{$attributeId}" => [
                                "{$attribute->name} harus berupa teks.",
                            ],
                        ]);
                    }
                    break;
            }
        }
    }

    /**
     * Memvalidasi kode wilayah layanan jasa.
     * Setiap item hanya boleh berisi satu tingkat wilayah.
     */
    private function validateServiceAreas(array $areas): void
    {
        $seen = [];

        foreach ($areas as $index => $area) {
            $codes = array_filter([
                'village_code' => $area['village_code'] ?? null,
                'district_code' => $area['district_code'] ?? null,
                'regency_code' => $area['regency_code'] ?? null,
            ], fn ($value) => $value !== null && $value !== '');

            if (count($codes) !== 1) {
                throw ValidationException::withMessages([
                    "service_areas.{$index}" => [
                        'Setiap wilayah layanan harus memilih tepat satu tingkat wilayah.',
                    ],
                ]);
            }

            $field = array_key_first($codes);
            $code = (string) $codes[$field];

            $exists = match ($field) {
                'village_code' => Village::where('code', $code)->exists(),
                'district_code' => District::where('code', $code)->exists(),
                'regency_code' => Regency::where('code', $code)->exists(),
            };

            if (!$exists) {
                throw ValidationException::withMessages([
                    "service_areas.{$index}.{$field}" => [
                        'Kode wilayah layanan tidak ditemukan.',
                    ],
                ]);
            }

            $uniqueKey = $field . ':' . $code;

            if (isset($seen[$uniqueKey])) {
                throw ValidationException::withMessages([
                    "service_areas.{$index}.{$field}" => [
                        'Wilayah layanan tidak boleh duplikat.',
                    ],
                ]);
            }

            $seen[$uniqueKey] = true;
        }
    }

    /**
     * Membuat slug unik.
     */
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}