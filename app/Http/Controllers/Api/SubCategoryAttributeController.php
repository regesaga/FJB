<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubCategoryAttributeController extends Controller
{
    /**
     * Mengambil pertanyaan dinamis berdasarkan:
     * - Subkategori
     * - Jenis iklan (product/service)
     */
    public function index(
        Request $request,
        int $subCategoryId
    ): JsonResponse {

        $validated = $request->validate([
            'listing_type' => [
                'required',
                'string',
                Rule::in(['product', 'service']),
            ],
        ]);

        $listingType = $validated['listing_type'];

        $subCategory = SubCategory::query()
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->with('category')
            ->findOrFail($subCategoryId);

        /*
         * Koleksi pertanyaan.
         */
        $questions = collect();

        /*
         * 1. Pertanyaan umum barang dari kategori.
         *
         * Hanya diambil ketika listing_type = product.
         */
        if ($listingType === 'product') {

            $categoryAttributes = $subCategory->category
                ->categoryAttributes()
                ->where('visible', true)
                ->with([
                    'productAttribute.options' => function ($query) {
                        $query->orderBy('sort')
                            ->orderBy('id');
                    },
                ])
                ->orderBy('sort')
                ->orderBy('id')
                ->get();

            foreach ($categoryAttributes as $mapping) {

                $attribute = $mapping->productAttribute;

                if (!$attribute) {
                    continue;
                }

                $questions->put($attribute->id, [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'slug' => $attribute->slug,
                    'type' => $attribute->type,
                    'required' => (bool) $mapping->required,
                    'filterable' => (bool) $mapping->filterable,
                    'searchable' => (bool) $mapping->searchable,
                    'options' => $attribute->options->map(
                        function ($option) {
                            return [
                                'value' => $option->value,
                            ];
                        }
                    )->values(),
                    'source' => 'category',
                    'sort' => $mapping->sort,
                ]);
            }
        }

        /*
         * 2. Pertanyaan khusus subkategori.
         *
         * Mengambil pertanyaan sesuai jenis iklan
         * atau pertanyaan yang berlaku untuk keduanya.
         */
        $subcategoryAttributes = $subCategory
            ->subCategoryAttributes()
            ->where('visible', true)
            ->whereIn('listing_type', [$listingType, 'both'])
            ->with([
                'productAttribute.options' => function ($query) {
                    $query->orderBy('sort')
                        ->orderBy('id');
                },
            ])
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        foreach ($subcategoryAttributes as $mapping) {

            $attribute = $mapping->productAttribute;

            if (!$attribute) {
                continue;
            }

            // Pertanyaan subkategori menggantikan konfigurasi
            // kategori jika atributnya sama.
            $questions->put($attribute->id, [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'slug' => $attribute->slug,
                'type' => $attribute->type,
                'required' => (bool) $mapping->required,
                'filterable' => (bool) $mapping->filterable,
                'searchable' => (bool) $mapping->searchable,
                'options' => $attribute->options->map(
                    function ($option) {
                        return [
                            'value' => $option->value,
                        ];
                    }
                )->values(),
                'source' => 'subcategory',
                'sort' => $mapping->sort,
            ]);
        }

        /*
         * Urutkan pertanyaan berdasarkan sort,
         * kemudian hilangkan field internal sort.
         */
        $questions = $questions
            ->sortBy('sort')
            ->values()
            ->map(function ($question) {
                unset($question['sort']);

                return $question;
            });

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil diambil.',
            'data' => [
                'subcategory' => [
                    'id' => $subCategory->id,
                    'name' => $subCategory->name,
                    'slug' => $subCategory->slug,
                ],
                'listing_type' => $listingType,
                'attributes' => $questions,
            ],
        ]);
    }
}