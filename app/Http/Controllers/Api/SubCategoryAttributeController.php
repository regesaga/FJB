<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;

class SubCategoryAttributeController extends Controller
{
    /**
     * Mengambil pertanyaan dinamis berdasarkan subkategori.
     */
    public function index(int $subCategoryId): JsonResponse
    {
        $subCategory = SubCategory::query()
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->findOrFail($subCategoryId);

        $questions = $subCategory->subCategoryAttributes()
            ->where('visible', true)
            ->with([
                'productAttribute.options' => function ($query) {
                    $query->orderBy('sort')
                        ->orderBy('id');
                },
            ])
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function ($mapping) {
                $attribute = $mapping->productAttribute;

                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'slug' => $attribute->slug,
                    'type' => $attribute->type,
                    'required' => (bool) $mapping->required,
                    'filterable' => (bool) $mapping->filterable,
                    'searchable' => (bool) $mapping->searchable,
                    'options' => $attribute->options->map(function ($option) {
                        return [
                            'value' => $option->value,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil diambil.',
            'data' => [
                'subcategory' => [
                    'id' => $subCategory->id,
                    'name' => $subCategory->name,
                    'slug' => $subCategory->slug,
                ],
                'attributes' => $questions,
            ],
        ]);
    }
}